<?php
namespace App\Controllers;

use Pulse\Core\App;
use Pulse\Http\Request;
use Pulse\Http\Response;

class HomeController
{
    public function index(Request $req, $ctx): Response
    {
        $app = App::getInstance();

        // Pass data to view
        return $app->view('home', [
            'title' => 'Welcome to Pulse'
        ]);
    }

    public function health(Request $req, $ctx): Response
    {
        $res = new Response();
        return $res->json([
            'status' => 'ok',
            'message' => 'Pulse is healthy!'
        ]);
    }
}
