<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Products;

class ProductsController extends Controller
{
    private function displayJson(Request $request, Response $response, $json_data)
    {
        $json = json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $response->getBody()->write($json);
        return $response->withHeader('Content-type', 'application/json');
    }

    public function getProductsList(Request $request, Response $response, array $args)
    {
        echo "<pre>";
        var_dump($args);
        echo "</pre>";
        echo "<pre>";
        var_dump($request->getParsedBody());
        echo "</pre>";

        $prodcut = new Products();
        $data = $prodcut->getDate();
        $res_json = array(
            'success' => 'success',
            'message' => 'sucess',
            "details" => $data,
            "details_url" => "https://example.jp/response"
        );

        return $this->displayJson($request, $response, $res_json);
    }

    public function getProduct(Request $request, Response $response, array $args)
    {
        $prodcut = new Products();
        $data = $prodcut->getDate();
        $keys = (int) $args['id'];
        $res_json = array(
            'success' => '',
            'message' => '',
            "details" => [],
            "details_url" => "https://example.jp/response"
        );

        foreach ($data as $key => $value) {
            if (strcmp($value["id"], $keys) == 0) {
                $res_json['details'][0] = $value;
                break;
            }
        }

        if ($res_json['details'] == []) {
            $res_json['success'] = 'failure';
            $res_json['message'] = 'error: No such product_id';
        } else {
            $res_json['success'] = 'success';
            $res_json['message'] = 'sucess';
        }

        return $this->displayJson($request, $response, $res_json);
    }
}
