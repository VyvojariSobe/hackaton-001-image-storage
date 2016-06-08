<?php
namespace Hackaton\ImageStorage\Image\Storages;

use Hackaton\ImageStorage\Image\File;
use Hackaton\ImageStorage\Image\StoredFile;

interface IStorage
{
  /**
   * @param string $profile
   * @param File   $file
   * @return StoredFile
   */
    public function save($profile, File $file);

  /**
   * @param string $profile
   * @param string $key
   * @return StoredFile
   */
    public function load($profile, $key);

    public function exists($profile, $key);

    public function delete($profile, $key);
}
