<?php

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

### Init

$request = ServerRequestFactory::fromGlobals();

### Action

$name = $request->getQueryParams()['name'] ?? 'Guest';
$body = 'Hello, ' . $name . '!';
$response = (new HtmlResponse($body))
        ->withHeader('X-Developer', 'artemkjv');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);