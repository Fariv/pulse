#!/usr/bin/env php
<?php
declare(strict_types=1);

use Pulse\Core\App;
use Pulse\Http\Request;
use Pulse\Http\Response;

require __DIR__ . '/../vendor/autoload.php';

// ------------------------
// Load user routes (app space)
// ------------------------
$routesPath = __DIR__ . '/../app/Routes.php';
if (file_exists($routesPath)) {
    require $routesPath;
}

// ------------------------
// Server configuration
// ------------------------
$host = '0.0.0.0';
$port = 9501;

// ------------------------
// Create OpenSwoole HTTP server
// ------------------------
$server = new Swoole\Http\Server($host, $port);

$server->on('request', function (Swoole\Http\Request $swooleReq, Swoole\Http\Response $swooleResp) {

    $request = Request::fromSwoole($swooleReq);

    // -------------------------------
    // Serve static assets
    // -------------------------------
    $uri = $request->getPath();
    if (strpos($uri, '/assets/') === 0) {
        $file = __DIR__ . '/../' . ltrim($uri, '/');
        if (file_exists($file)) {
            $swooleResp->header('Content-Type', mime_content_type($file));
            $swooleResp->end(file_get_contents($file));
            return;
        } else {
            $swooleResp->status(404);
            $swooleResp->end('Asset not found');
            return;
        }
    }

    // -------------------------------
    // Handle dynamic routes via Pulse
    // -------------------------------
    $app = App::getInstance();
    $response = $app->handle($request);

    // Send response back
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            $swooleResp->header($name, $value);
        }
    }

    $swooleResp->status($response->getStatusCode());
    $swooleResp->end((string)$response->getBody());
});

echo "Pulse server started at http://{$host}:{$port}\n";
$server->start();
