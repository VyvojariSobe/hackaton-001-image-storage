<?php

namespace Hackaton\ImageStorage\Command;

use Hackaton\ImageStorage\Image\Manager;
use Hackaton\ImageStorage\Image\Processor\Processor;
use Hackaton\ImageStorage\Image\Storages\Md5Split3PairsAsLevelsStorage;
use Hackaton\ImageStorage\Image\StoredFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class RenderNewImagesCommand extends Command
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var Md5Split3PairsAsLevelsStorage
     */
    private $storage;


    public function configure()
    {
        $this
            ->setName('render:image')
            ->addArgument(
                'profile',
                InputArgument::OPTIONAL,
                'Image profile for new render' // Překlad
            )
            ->setDescription('Render new images');
    }


    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->parameters = require __DIR__ . '/../config.php';

        $processor = new Processor();
        $this->storage = new Md5Split3PairsAsLevelsStorage($this->parameters['storageDir']);
        $this->manager = new Manager($processor, $this->storage, $this->parameters['profiles']);
    }


    public function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);

        $profile = $input->getArgument('profile');

        if (!array_key_exists($profile, $this->parameters['profiles'])) {
            throw new \InvalidArgumentException('Invalid image profile.');
        }

        $output->writeln('Start processing images');

        $finder = new Finder();
        $folderWithOriginalImages = $this->parameters['storageDir'] . '/original/';
        $finder->files()->in($folderWithOriginalImages);

        $progress = new ProgressBar($output, count($finder));
        $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');

        foreach ($finder as $file) {
            $name = explode('.', $file->getFileName());

            $storedFile = new StoredFile($name[0], $file->getPathName());

            $this->manager->renderProfileImage($storedFile, $profile);

            $progress->advance();
        }

        $progress->finish();
        $output->writeln('');

    }
}
