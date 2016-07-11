<?php
namespace Hackaton\ImageStorage\Container;

use Hackaton\ImageStorage\Exceptions\ServiceMustBeObjectException;
use Hackaton\ImageStorage\Exceptions\ServiceNotFoundException;

trait ContainerTrait
{
    protected $parameters = [];
    protected $services = [];

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function getParameter($key, $default = null)
    {
        if (isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }

        return $default;
    }

    public function setService($name, $service)
    {
        if (!is_object($service)) {
            throw new ServiceMustBeObjectException(sprintf('Service factory must return object, got "%s"!', gettype($service)));
        }

        return $this->services[$name] = $service;
    }

    public function getService($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        if (!method_exists($this, $name . 'Factory')) {
            throw new ServiceNotFoundException(sprintf('Service "%s" not found!', $name));
        }

        $service = $this->{$name . 'Factory'}();

        if (!is_object($service)) {
            throw new ServiceMustBeObjectException(sprintf('Service factory must return object, got "%s"!', gettype($service)));
        }

        return $this->services[$name] = $service;
    }
}
