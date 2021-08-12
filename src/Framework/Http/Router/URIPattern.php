<?php


namespace Framework\Http\Router;


class URIPattern implements IPattern
{

    private Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function generate(): string
    {
        $route = $this->route;
        $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use ($route){
            $argument = $matches[1]; // 'id'
            $replace = $route->getTokens()[$argument] ?? '[^}]+';
            return '(?P<' . $argument . '>' . $replace . ')';
        }, $route->getPattern());
        return $pattern;
    }
}