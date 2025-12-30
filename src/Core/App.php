<?php
namespace Pulse\Core;

use Pulse\Router\Route;
use Pulse\Http\Request;
use Pulse\Http\Response;

class App
{
    protected static ?self $instance = null;

    /** @var Route[] */
    protected array $routes = [];

    protected array $middleware = [];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register GET route
     */
    public function get(string $path, callable|array $handler): void
    {
        $this->routes[] = new Route('GET', $path, $handler);
    }

    /**
     * Register POST route
     */
    public function post(string $path, callable|array $handler): void
    {
        $this->routes[] = new Route('POST', $path, $handler);
    }

    /**
     * Handle request
     */
    public function handle(Request $request): Response
    {
        $ctx = new Context();

        $requestPath = $request->server['request_uri'] ?? '/';
        $requestMethod = $request->server['request_method'] ?? 'GET';

        foreach ($this->routes as $route) {
            if ($route->method === $requestMethod) {
                $params = $route->match($requestPath);
                if ($params !== null) {
                    return $route->run($request, $ctx, $params);
                }
            }
        }

        $response = new Response();
        $response->status(404);
        $response->write('Not Found');
        return $response;
    }

    /**
     * Run handler (supports callable or [Class, method])
     */
    protected function runHandler(callable|array $handler, Request $request, Context $ctx): Response
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $instance = new $class();
            return $instance->$method($request, $ctx);
        }

        return $handler($request, $ctx);
    }

    public function addMiddleware(callable $mw): void
    {
        $this->middleware[] = $mw;
    }


    protected function runMiddleware(Request $request, Context $ctx): bool
    {
        foreach ($this->middleware as $mw) {
            // If middleware returns false, stop processing
            if ($mw($request, $ctx) === false) {
                return false;
            }
        }
        return true;
    }

    // Directory where views are located
    protected string $viewsPath = __DIR__ . '/../../app/Views/';

    public function setViewsPath(string $path): void
    {
        $this->viewsPath = $path;
    }

    /**
     * Render a view
     *
     * @param string $view Name of the view file (without .php)
     * @param array $data Associative array of variables for the view
     * @return Response
     */
    public function view(string $view, array $data = []): Response
    {
        $res = new Response();

        // Extract variables for use in view
        extract($data);

        // Start output buffering
        ob_start();

        // Replace slashes with DIRECTORY_SEPARATOR
        $viewFile = $this->viewsPath . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewFile)) {
            $res->status(500);
            $res->write("View file not found: $viewFile");
            return $res;
        }

        include $viewFile;

        $html = ob_get_clean();

        $res->header('Content-Type', 'text/html');
        $res->write($html);

        return $res;
    }
}
