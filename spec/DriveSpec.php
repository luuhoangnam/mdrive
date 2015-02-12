<?php

namespace spec\mBear\Drive;

use Illuminate\Contracts\Filesystem\Factory as FileSystem;
use mBear\Drive\Drive;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DriveSpec extends ObjectBehavior
{
    private $config;

    public function let(FileSystem $filesystem)
    {
        $this->beConstructedWith($filesystem);

        $this->config = require_once __DIR__ . '/../config/config.php';
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Drive::class);
    }

    public function it_should_store_upload_file()
    {
        $this->store('upload_file')->shouldReturn(true);

        dd($this->config['store_path']);
    }
}
