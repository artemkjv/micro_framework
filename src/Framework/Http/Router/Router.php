<?php


namespace Framework\Http\Router;


use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class Router
{

    private RouteCollection $routes;

    /**
     * Router constructor.
     * @param RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    // Поиска соответствуещего запросу роута
    public function match(ServerRequestInterface $request): Result{
        foreach ($this->routes->getRoutes() as $route){

            // Проверка на соответствие метода запроса и роута
            if($route->getMethods() && !in_array($request->getMethod(), $route->getMethods(), true))
                continue;

            // Генерируем регулярное выражение
            $uri_pattern = new URIPattern($route);
            $pattern = $uri_pattern->generate();

            // Проверка на соответствие паттерна и пути запроса
            if(preg_match('~^' . $pattern . '$~i', $request->getUri()->getPath(), $matches)){
                return new Result(
                    $route->getName(),
                    $route->getHandler(),
                    array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
                );
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate(string $name, array $params = []): string {
        $arguments = array_filter($params);
        foreach ($this->routes->getRoutes() as $route){
            if($name !== $route->getName())
                continue;

            $url = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$arguments){
                $argument = $matches[1];
                if(!array_key_exists($argument, $arguments))
                    throw new \InvalidArgumentException('Missing parameter "' . $argument . '"');

                return $arguments[$argument];
            }, $route->getPattern());

            if($url != null){
                return $url;
            }
        }
        throw new RouteNotFoundException($name, $params);
    }

}