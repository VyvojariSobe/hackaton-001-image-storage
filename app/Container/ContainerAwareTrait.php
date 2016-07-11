<?php
namespace Hackaton\ImageStorage\Container;

trait ContainerAwareTrait
{
  /** @var IContainer */
    protected $container;

    public function __construct(IContainer $container)
    {
        $this->container = $container;
    }
}
