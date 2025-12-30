<?php
namespace Pulse\Router;

use Pulse\Core\Context;
use Pulse\Http\Request;
use Pulse\Http\Response;

class Route
{
    public string $method;
    public string $path;
    public $handler;
    protected array $paramNames = [];
    protected string $regex;

    public function __construct(string $method, string $path, callable|array $handler)
    {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->handler = $handler;

        // Convert path to regex for parameters, e.g., /user/{id}
        $this->regex = preg_replace_callback('/\{(\w+)\}/', function($matches) {
            $this->paramNames[] = $matches[1];
            return '([^/]+)';
        }, $path);
        $this->regex = "#^" . $this->regex . "$#";
    }

    public function match(string $requestPath): ?array
    {
        if (preg_match($this->regex, $requestPath, $matches)) {
            array_shift($matches); // first is full match
            return array_combine($this->paramNames, $matches);
        }
        return null;
    }

    public function run(Request $request, Context $ctx, array $params = []): Response
    {
        foreach ($params as $k => $v) {
            $ctx->set($k, $v);
        }

        if (is_array($this->handler)) {
            [$class, $method] = $this->handler;
            $instance = new $class();
            return $instance->$method($request, $ctx);
        }

        return $this->handler($request, $ctx);
    }
}
