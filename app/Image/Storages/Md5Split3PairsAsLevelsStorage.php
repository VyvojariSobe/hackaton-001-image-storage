<?php
namespace Hackaton\ImageStorage\Image\Storages;

use Hackaton\ImageStorage\Exceptions\CantCreateDirectoryException;
use Hackaton\ImageStorage\Exceptions\DirectoryNotFoundException;
use Hackaton\ImageStorage\Image\File;
use Hackaton\ImageStorage\Image\StoredFile;

class Md5Split3PairsAsLevelsStorage implements IStorage
{
    protected $dir;

    public function __construct($base_dir)
    {
        $dir = realpath($base_dir);

        if ($dir === false) {
            throw new DirectoryNotFoundException(sprintf('Directory "%s" not found', $base_dir));
        }

        $this->dir = $dir;
    }

    protected function generateIdFromKey($key)
    {
        return md5($key);
    }

    protected function generatePathById($profile, $id, $extension)
    {
        return "$this->dir/$profile/$id[0]$id[1]/$id[2]$id[3]/$id[4]$id[5]/$id.$extension";
    }

    protected function preparePath($path)
    {
        $dir_path = dirname($path);

        if (is_dir($dir_path)) {
            return;
        }

        @mkdir($dir_path, 0755, true);

        if (!is_dir($dir_path)) {
            throw new CantCreateDirectoryException(sprintf('Can\'t create directory "%s".', $dir_path));
        }
    }

    public function save($profile, File $file)
    {
        $id = $this->generateIdFromKey($file->getKey());
        $path = $this->generatePathById($profile, $id, 'jpg');
        $this->preparePath($path);
        @file_put_contents($path, $file->getContent());

        return new StoredFile($file->getKey(), $path);
    }

    public function load($profile, $key)
    {
        $id = $this->generateIdFromKey($key);
        $path = $this->generatePathById($profile, $id, 'jpg');

        if (!is_file($path)) {
            return false;
        }

        $stored_file = new StoredFile($key, $path);

        return $stored_file;
    }

    public function exists($profile, $key)
    {
        $id = $this->generateIdFromKey($key);
        $path = $this->generatePathById($profile, $id, 'jpg');

        return is_file($path);
    }

    public function delete($profile, $key)
    {
        $id = $this->generateIdFromKey($key);
        $path = $this->generatePathById($profile, $id, 'jpg');
        @unlink($path);

        return !is_file($path);
    }
}
