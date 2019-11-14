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
        // $prodcut = new Products();
        // $data = $this->prodcut->getData();
        $res_json = array(
            'success' => 'success',
            'message' => 'sucess',
            "details" => $this->data,
            "details_url" => "https://example.jp/response"
        );

        return $this->displayJson($request, $response, $res_json);
    }

    public function getProduct(Request $request, Response $response, array $args)
    {
        // $prodcut = new Products();
        // $data = $prodcut->getData();
        $keys = (int) $args['id'];
        $res_json = array(
            'success' => '',
            'message' => '',
            "details" => [],
            "details_url" => "https://example.jp/response"
        );

        foreach ($this->data as $key => $value) {
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

    // 商品の登録
    public function addProduct(Request $request, Response $response, array $args)
    {
        $next_id = count($this->data) + 1;
        // $res_json_str = json_encode($request->getParsedBody(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $res_json_str = json_decode(json_encode($request->getParsedBody()), TRUE);
        // $add_data = "
        // {
        //     'id': {$next_id},
        //     'name': ,
        //     'description': '東京で開かれた時用のチケット．東京で開かれる可能性は果てしなく小さい',
        //     'image': '/images/02.png',
        //     'price': '15000'
        // }";

        $res_json_str['id'] = $next_id;
        $this->data[$next_id - 1] = $res_json_str;
        $this->prodcut->addData();


        // echo $next_id;
        return $this->displayJson($request, $response, $this->data);
    }
}
