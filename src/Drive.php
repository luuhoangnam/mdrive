<?php

namespace mBear\Drive;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Factory as FileSystem;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as Validator;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Drive
{
    /**
     * @var array
     */
    protected $editCommands;

    /**
     * @var FileSystem|\Illuminate\Filesystem\Filesystem
     */
    private $filesystem;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ViewFactory
     */
    private $viewFactory;
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var ImageManager
     */
    private $imageManager;
    /**
     * @var Config
     */
    private $config;

    /**
     * @param FileSystem   $filesystem
     * @param Request      $request
     * @param ViewFactory  $viewFactory
     * @param Validator    $validator
     * @param ImageManager $imageManager
     */
    public function __construct(
        FileSystem $filesystem,
        Request $request,
        ViewFactory $viewFactory,
        Validator $validator,
        ImageManager $imageManager,
        Config $config
    ) {
        $this->filesystem   = $filesystem;
        $this->request      = $request;
        $this->viewFactory  = $viewFactory;
        $this->validator    = $validator;
        $this->imageManager = $imageManager;
        $this->config       = $config;
    }

    /**
     * @param string $input
     *
     * @return string
     * @throws ValidationFailedException
     */
    public function store($input = null)
    {
        $uploadedFile = null;

        if ($input) {
            if ( ! $this->request->hasFile($input)) {
                throw new \InvalidArgumentException("Input does not exists in request");
            }

            $uploadedFile = $this->request->file($input);
        }

        if (is_null($input)) {
            foreach ($this->config->get('storage.default_input') as $inputName) {
                if ($this->request->hasFile($inputName)) {
                    $uploadedFile = $this->request->file($inputName);
                    break;
                }
            }
        }

        if ( ! $uploadedFile) {
            throw new \InvalidArgumentException("Input does not exists in request");
        }

        // Validation
        $validation = $this->validator->make([
            'file' => $uploadedFile
        ], [
            'file' => $this->config->get('storage.validation')
        ]);

        if ($validation->fails()) {
            throw new ValidationFailedException($validation->errors());
        }

        // Process file

        // Edit file
        // TODO Support more processor instead only intervention/image
        $image = $this->imageManager->make($uploadedFile->getRealPath());
        foreach ($this->editCommands as $cmd => $params) {
            call_user_func_array([$image, $cmd], $params);
        }
        $image->save();

        // Save file
        $relativeStoragePath = $this->config->get('storage.storage_path');
        $storagePath         = public_path($relativeStoragePath);
        // TODO Parse file name (temporary hardcoded)
        $now          = Carbon::now();
        $relativePath = "{$now->year}/{$now->month}";
        $this->filesystem->makeDirectory("$relativeStoragePath/$relativePath", 0755, true, true);

        /** @var UploadedFile $uploadedFile */
        $fileName = $uploadedFile->getClientOriginalName();

        // @TODO Handle duplicate file name
        $uploadedFile->move("{$storagePath}/{$relativePath}", $fileName);

        return "{$relativePath}/$fileName";
    }

    /**
     * @param string $path
     *
     * @return File
     */
    public function file($path)
    {
        $relativeStoragePath = $this->config->get('storage.storage_path');
        $fullPath            = "{$relativeStoragePath}/$path";

        return new File($fullPath);
    }

    /**
     * @param string $command
     * @param        ...$parameters
     *
     * @return $this
     */
    public function edit($command, ...$parameters)
    {
        $this->editCommands[$command] = $parameters;

        return $this;
    }
}