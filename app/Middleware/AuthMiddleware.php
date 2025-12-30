<?php
namespace App\Middleware;

use Pulse\Http\Request;
use Pulse\Core\Context;

class AuthMiddleware
{
    public function __invoke(Request $req, Context $ctx): bool
    {
        // Only check POST requests to /login
        if (($req->server['request_uri'] ?? '') === '/login' && ($req->server['request_method'] ?? '') === 'POST') {
            $username = $req->post['username'] ?? '';
            $password = $req->post['password'] ?? '';

            // Simple static check
            if ($username === 'admin' && $password === 'secret') {
                $ctx->set('user', 'admin');
                return true; // allow
            }

            // Stop request if credentials invalid
            return false;
        }

        // For other routes, allow by default
        return true;
    }
}
