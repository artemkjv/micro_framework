<?php

use Laminas\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

### Init

$request = ServerRequestFactory::fromGlobals();

