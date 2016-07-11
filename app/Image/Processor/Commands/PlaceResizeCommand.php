<?php
namespace Hackaton\ImageStorage\Image\Processor\Commands;

use Nette\Utils\Image;

class PlaceResizeCommand implements ICommand
{
    public function execute(Image $image, array $params = [])
    {
        $image->resize($params[0], $params[1], $params[2]);

        $blank = Image::fromBlank($params[0], $params[1], Image::rgb(255, 255, 255));
        $blank->place($image, '50%', '50%');

        return $blank;
    }
}
