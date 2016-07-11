<?php
namespace Hackaton\ImageStorage\Image;

class StoredFile extends File
{
    protected $key;
    protected $path;
    protected $content;
    protected $mime_type;

    public function __construct($key, $path)
    {
        $this->path = realpath($path);

        parent::__construct($key, null);
    }

    public function getContent()
    {
        if (!$this->content) {
            $this->content = file_get_contents($this->path);
        }

        return $this->content;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getFilesize()
    {
        return filesize($this->path);
    }

    public function getUpdatedTime()
    {
        return filemtime($this->path);
    }

    public function getMimeType()
    {
        if (!$this->mime_type) {
            $this->mime_type = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->getContent());
        }

        return $this->mime_type;
    }
}
