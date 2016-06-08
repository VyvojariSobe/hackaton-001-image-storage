<?php
namespace Hackaton\ImageStorage\Image\Processor\Commands;

use Nette\Utils\Image;

class FilterCommand implements ICommand
{
    public function execute(Image $image, array $params = [])
    {
        $image->filter($params[0]);
    }
}
