<?php
namespace Hackaton\ImageStorage\Image\Processor\Commands;

use Nette\Utils\Image;

class ResizeCommand implements ICommand
{
    public function execute(Image $image, array $params = [])
    {
        $image->resize($params[0], $params[1], $params[2]);
    }
}
