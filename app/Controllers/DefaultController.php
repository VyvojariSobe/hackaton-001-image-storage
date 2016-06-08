<?php
namespace Hackaton\ImageStorage\Controllers;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function readAction()
    {
        return new Response(file_get_contents(__DIR__ . '/../Views/Default.read.html'));
    }
}
