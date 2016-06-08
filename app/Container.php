<?php
namespace Hackaton\ImageStorage;

use Hackaton\ImageStorage\Container\ContainerTrait;
use Hackaton\ImageStorage\Container\IContainer;
use Hackaton\ImageStorage\Image\Manager;
use Hackaton\ImageStorage\Image\Processor\Processor;
use Hackaton\ImageStorage\Image\Providers\SymfonyRequestProvider;
use Hackaton\ImageStorage\Image\Storages\Md5Split3PairsAsLevelsStorage;
use Hackaton\ImageStorage\Loaders\GuzzleLoader;
use Hackaton\ImageStorage\Loaders\ILoader;
use Hackaton\ImageStorage\Resolvers\MvcResolver;

class Container implements IContainer
{
    use ContainerTrait;

    public function callableResolverFactory()
    {
        $service = new MvcResolver();

        return $service;
    }

    public function loaderFactory()
    {
        $service = new GuzzleLoader();

        return $service;
    }

    public function imageManagerFactory()
    {
        $processor = new Processor();
        $storage = new Md5Split3PairsAsLevelsStorage($this->getParameter('storageDir'));
        $service = new Manager(
            $processor,
            $storage,
            $this->getParameter('profiles'),
            $this->getParameter('excludedImages')
        );

        return $service;
    }

    public function symfonyRequestProviderFactory()
    {
        /** @var ILoader $loader */
        $loader = $this->getService('loader');
        $service = new SymfonyRequestProvider($loader);

        return $service;
    }
}
