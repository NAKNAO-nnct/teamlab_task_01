<?php

namespace App\Controllers;

require_once(__DIR__ . '/../Models/Products.php');

use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\DBConnector;
use App\Models\DataAccessProducts;

abstract class Controller
{
    public $db_access_conector;
    public function __construct()
    {
        $this->db_access_conector = new DataAccessProducts();
    }
}
