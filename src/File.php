<?php

namespace mBear\Drive;

use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * Class File
 *
 * @author  Nam Hoang Luu <nam@mbearvn.com>
 * @package App\Drive
 *
 */
class File extends SymfonyFile
{
    /**
     * @return bool
     */
    public function delete()
    {
        $filesystem = $this->makeFileSystem();

        return $filesystem->delete($this->getPathName());
    }

    /**
     * @return Filesystem
     */
    private function makeFileSystem()
    {
        return app('filesystem');
    }
}
