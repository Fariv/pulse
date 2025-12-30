<?php
use Pulse\Core\App;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;

use Pulse\Http\Request;
use Pulse\Http\Response;

$app = App::getInstance();

// add middleware here if needed
$app->addMiddleware(new AuthMiddleware());

$app->get('/', [HomeController::class, 'index']);
$app->get('/health', [HomeController::class, 'health']);

// Example login route
use App\Controllers\AuthController;
// Define POST /login route
$app->post('/login', [AuthController::class, 'attempt']);
