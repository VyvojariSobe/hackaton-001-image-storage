<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Hackaton\ImageStorage\Command\RenderNewImagesCommand;

require __DIR__ . '/../vendor/autoload.php';

$input = new ArgvInput();
$application = new Application('Image Storage API', '1.0');
$application->add(new RenderNewImagesCommand());
$application->run();