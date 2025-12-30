<?php
namespace App\Controllers;

use Pulse\Http\Request;
use Pulse\Http\Response;
use Pulse\Core\Context;

class AuthController
{
    public function attempt(Request $req, Context $ctx): Response
    {
        $res = new Response();

        // If middleware failed, user is not set
        $user = $ctx->get('user') ?? null;

        if ($user) {
            $res->status(200);
            $res->write(json_encode([
                'status' => 'success',
                'user' => $user,
                'message' => 'Login successful'
            ]));
        } else {
            $res->status(401);
            $res->write(json_encode([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ]));
        }

        $res->header('Content-Type', 'application/json');
        return $res;
    }
}
