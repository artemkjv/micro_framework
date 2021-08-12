<?php

use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

### Init

$request = ServerRequestFactory::fromGlobals();

$routes = new RouteCollection();
$router = new Router($routes);

### Action

$result = $router->match($request);

