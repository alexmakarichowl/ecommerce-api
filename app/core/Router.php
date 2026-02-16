<?php

class Router {

    private array $routes = [];

    public function add(string $method, string $uri, $action) {
        $this->routes[] = compact('method', 'uri', 'action');
    }

    public function dispatch() {
        try {
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $requestMethod = $_SERVER['REQUEST_METHOD'];

            foreach ($this->routes as $route) {
                if ($route['uri'] === $requestUri &&
                    $route['method'] === $requestMethod) {

                    [$controller, $method] = $route['action'];

                    if (!method_exists($controller, $method)) {
                        Response::json(["error" => "Method not found"], 500);
                        return;
                    }

                    (new $controller)->$method();
                    return;
                }
            }

            Response::json(["error" => "Not Found"], 404);
        } catch (Exception $e) {
            error_log('Router error: ' . $e->getMessage());
            Response::json(["error" => "Internal Server Error"], 500);
        }
    }
}
