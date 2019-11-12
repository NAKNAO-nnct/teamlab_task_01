<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Controller
{
    public function __construct(ContainerInterface $container)
    { }
}
