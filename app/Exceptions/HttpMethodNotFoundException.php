<?php

namespace Hackaton\ImageStorage\Exceptions;

class HttpMethodNotFoundException extends HttpException
{
    protected $code = 405;
}
