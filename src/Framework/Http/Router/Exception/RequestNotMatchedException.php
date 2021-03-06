<?php


namespace Framework\Http\Router\Exception;


use Psr\Http\Message\ServerRequestInterface;

class RequestNotMatchedException extends \LogicException
{

    private ServerRequestInterface $request;

    /**
     * RequestNotMatchedException constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct('Matches not found.');
        $this->request = $request;
    }

    public function getRequest(): ServerRequestInterface{
        return $this->request;
    }

}