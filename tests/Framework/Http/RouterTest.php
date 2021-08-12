<?php


namespace Framework\Http;


use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class RouterTest extends TestCase
{

    public function testCorrectMethod(){
        $routes = new RouteCollection();
        $routes->get($name_get = 'blog', '/blog', $handler_get = 'handler_get');
        $routes->post($name_post = 'blog_edit', '/blog', $handler_post = 'handler_post');

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        $this->assertEquals($name_get, $result->getName());
        $this->assertEquals($handler_get, $result->getHandler());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        $this->assertEquals($name_post, $result->getName());
        $this->assertEquals($handler_post, $result->getHandler());
    }

    public function testMissingMethod(){
        $routes = new RouteCollection();
        $routes->post('blog', '/blog', 'handler_post');

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('DELETE', '/blog'));
    }

    public function testCorrectAttributes(){
        $routes = new RouteCollection();
        $routes->get($name = 'blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);
        $router = new Router($routes);
        $result = $router->match($this->buildRequest('GET', '/blog/5'));
        $this->assertEquals($name, $result->getName());
        $this->assertEquals(['id' => '5'], $result->getAttributes());
    }

    public function testIncorrectAttributes(){
        $routes = new RouteCollection();
        $routes->get($name = 'blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);
        $router = new Router($routes);
        $this->expectException(RequestNotMatchedException::class);
        $result = $router->match($this->buildRequest('GET', '/blog/slug'));
    }

    public function testGenerateMissingAttributes(){
        $routes = new RouteCollection();
        $routes->get($name = 'blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);
        $router = new Router($routes);
        $this->expectException(\InvalidArgumentException::class);
        $router->generate('blog_show', ['slug' => 'post']);
    }

    public function buildRequest($method, $uri): ServerRequestInterface
    {
        return (new ServerRequest())
            ->withMethod($method)
            ->withUri(new Uri($uri));
    }

}