<?php
namespace Hackaton\ImageStorage\Image\Processor;

use Hackaton\ImageStorage\Exceptions\CommandNotFoundException;
use Hackaton\ImageStorage\Image\Processor\Commands\ICommand;
use Nette\Utils\Image;

class Processor
{

    /**
     * @param $name
     * @return ICommand
     * @throws CommandNotFoundException
     */
    protected function createCommand($name)
    {
        $name = ucfirst($name);
        $class_name = "\\Hackaton\\ImageStorage\\Image\\Processor\\Commands\\{$name}Command";

        if (!class_exists($class_name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" not found. (%s)', $name, $class_name));
        }

        return new $class_name;
    }



    public function applyCommands(Image $image, array $commands = [])
    {
        foreach ($commands as $command_data) {
            $name = array_shift($command_data);
            $command = $this->createCommand($name);
            $command->execute($image, $command_data);
        }
    }
}
