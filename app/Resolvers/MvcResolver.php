<?php
namespace Hackaton\ImageStorage\Resolvers;

use Hackaton\ImageStorage\Exceptions\ActionNotFoundException;
use Hackaton\ImageStorage\Exceptions\ControllerNotFoundException;
use Hackaton\ImageStorage\Exceptions\HttpMethodNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class MvcResolver implements ICallableResolver
{
    protected static $method_to_action = [
    'GET'    => 'readAction',
    'POST'   => 'createAction',
    'DELETE' => 'deleteAction'
    ];

    public function resolve(Request $request)
    {
        $method = $request->getMethod();

        if (!isset(self::$method_to_action[$method])) {
            throw new HttpMethodNotFoundException(sprintf('Method "%s" is not allowed.', $method));
        }

        $path = trim($request->getPathInfo(), '/');
        $path_parts = explode('/', $path) + [null];
        $controller_name = ucfirst(strtolower(array_shift($path_parts))) ?: 'Default';

        $controller_class = "\\Hackaton\\ImageStorage\\Controllers\\{$controller_name}Controller";

        if (!class_exists($controller_class)) {
            throw new ControllerNotFoundException(sprintf('Controller "%s" not found', $controller_name));
        }

        $action = self::$method_to_action[$method];

        if (!method_exists($controller_class, $action)) {
            throw new ActionNotFoundException(sprintf($action, ' for controller "%s" not found', $controller_name));
        }

        return [$controller_class, $action, $path_parts];
    }
}
