<?php

class HomeController {
    public function index() {
        Response::json([
            'message' => 'Ecommerce API',
            'routes' => [
                ['method' => 'GET', 'path' => '/products'],
                ['method' => 'POST', 'path' => '/products'],
                ['method' => 'POST', 'path' => '/register'],
                ['method' => 'POST', 'path' => '/login']
            ]
        ]);
    }
}
