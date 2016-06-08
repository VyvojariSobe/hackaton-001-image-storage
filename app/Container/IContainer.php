<?php
namespace Hackaton\ImageStorage\Container;

interface IContainer
{
    public function getService($name);

    public function getParameter($key, $default = null);
}
