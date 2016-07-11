<?php
namespace Hackaton\ImageStorage;

use Hackaton\ImageStorage\Container\IContainer;
use Hackaton\ImageStorage\Exceptions\ControllerMustReturnsResponseException;
use Hackaton\ImageStorage\Exceptions\HttpException;
use Hackaton\ImageStorage\Resolvers\ICallableResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
  /** @var IContainer */
    protected $container;

    public function __construct()
    {
        $parameters = require __DIR__ . '/config.php';
        $parameters['appDir'] = __DIR__;
        $this->container = new Container($parameters);
    }

    public function handle(Request $request)
    {
        try {
            /** @var ICallableResolver $resolver */
            $resolver = $this->container->getService('callableResolver');
            $target = $resolver->resolve($request);

            list($controller, $action, $params) = $target;
            array_unshift($params, $request);

            $controller = new $controller($this->container);
            $response = call_user_func_array([$controller, $action], $params);

            if (!$response instanceof Response) {
                throw new ControllerMustReturnsResponseException(sprintf('Expected "%s" got "%s"!', Response::class, gettype($response)));
            }

            return $response;
        } catch (HttpException $e) {
            $code = $e->getCode();
        } catch (\Exception $e) {
            $code = 500;
        }

        return new Response($e->getMessage(), $code);
    }
}
