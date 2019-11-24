<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Products;

abstract class Controller
{
    public $prodcut;
    public $data;
    public function __construct()
    {
        $this->prodcut = new Products();
        $this->data = $this->prodcut->getData();
    }
}

