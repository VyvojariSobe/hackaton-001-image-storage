<?php

namespace Hackaton\ImageStorage\Controllers;

use Hackaton\ImageStorage\Container\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tracy\Debugger;

class ConfigurationController
{
    use ContainerAwareTrait;

    public function readAction()
    {
        $profiles = $this->container->getParameter('profiles');

        return new JsonResponse($profiles);
    }
}
