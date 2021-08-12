<?php


namespace Framework\Http\Router;


class RouteCollection
{

    private array $routes = [];

    // Добавление нового GET роута
    public function get(string $name, string $pattern, string $handler, array $tokens = []): void{
        $this->routes[] = new Route($name, $pattern, $handler, ['GET'], $tokens);
    }

    // Добавление нового POST роута
    public function post(string $name, string $pattern, string $handler, array $tokens = []): void{
        $this->routes[] = new Route($name, $pattern, $handler, ['POST'], $tokens);
    }

    public function getRoutes(): array{
        return $this->routes;
    }

}