<?php

class ProductController {

    public function index() {
        $product = new Product();
        Response::json($product->getAll());
    }

    public function store() {

        AuthMiddleware::handle();

        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if ($data === null) {
                Response::json(["error" => "Invalid JSON"], 400);
                return;
            }

            $product = new Product();
            $id = $product->create($data);

            Response::json(["message" => "Product created", "id" => $id], 201);

        } catch (Exception $e) {
            Response::json(["error" => $e->getMessage()], 400);
        }
    }
}
