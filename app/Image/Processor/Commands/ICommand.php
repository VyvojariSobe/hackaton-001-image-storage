<?php
namespace Hackaton\ImageStorage\Image\Processor\Commands;

use Nette\Utils\Image;

interface ICommand
{
    public function execute(Image $image, array $params = []);
}
