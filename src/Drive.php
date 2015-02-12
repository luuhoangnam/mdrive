<?php

namespace mBear\Drive;

use Illuminate\Contracts\Filesystem\Factory as FileSystem;

class Drive
{
    /**
     * @var FileSystem
     */
    private $filesystem;

    /**
     * @param FileSystem $filesystem
     */
    public function __construct(FileSystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $input
     *
     * @return bool
     */
    public function store($input = null)
    {
        return true;
    }
}
