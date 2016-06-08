<?php
namespace Hackaton\ImageStorage\Resolvers;

use Symfony\Component\HttpFoundation\Request;

interface ICallableResolver
{
    public function resolve(Request $request);
}
