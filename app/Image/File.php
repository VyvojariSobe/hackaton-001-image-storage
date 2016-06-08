<?php
namespace Hackaton\ImageStorage\Image;

class File
{
    protected $key;
    protected $content;
    protected $md5;

    public function __construct($key, $content)
    {
        $this->content = $content;
        $this->key = $key;
        $this->md5 = md5($content);
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getMd5()
    {
        return $this->md5;
    }
}
