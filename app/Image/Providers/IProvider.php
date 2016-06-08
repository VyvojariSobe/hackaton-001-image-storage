<?php
namespace Hackaton\ImageStorage\Image\Providers;

use Hackaton\ImageStorage\Image\File;

interface IProvider
{
  /**
   * @return File
   */
    public function createFile();
}
