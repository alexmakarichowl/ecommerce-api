<?php

$router = new Router();

// Add root route so visiting `/` in a browser returns a valid response
$router->add('GET', '/', ['HomeController', 'index']);

$router->add('POST', '/register', ['AuthController', 'register']);
$router->add('POST', '/login', ['AuthController', 'login']);
$router->add('GET', '/products', ['ProductController', 'index']);
$router->add('POST', '/products', ['ProductController', 'store']);

$router->dispatch();
