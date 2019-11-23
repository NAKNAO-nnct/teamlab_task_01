<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Products;

class ProductsController extends Controller
{
    // jsonを表示する
    private function displayJson(Request $request, Response $response, $json_data)
    {
        $json = json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $response->getBody()->write($json);
        return $response->withHeader('Content-type', 'application/json');
    }

    // レスポンスを生成
    private function generateResponse($success, $message, $details)
    {
        $res_json = array(
            'success' => $success,
            'message' => $message,
            "details" => $details,
            "details_url" => "https://example.jp/response"
        );

        return $res_json;
    }

    // 商品リストを取得
    public function getProductsList(Request $request, Response $response, array $args)
    {
        $res_json = $this->generateResponse('sccess', 'success', $this->data);

        return $this->displayJson($request, $response, $res_json);
    }

    // 商品を取得
    public function getProduct(Request $request, Response $response, array $args)
    {
        // get queryのkeyを取得
        $keys = (int) $args['id'];
        $details = [];

        // idの商品を取得
        foreach ($this->data as $key => $value) {
            if (strcmp($value["id"], $keys) == 0) {
                $details[0] = $value;
                break;
            }
        }

        if ($details == []) {
            $res_json = $this->generateResponse('failure', 'error: No such product_id', '');
        } else {
            $res_json = $this->generateResponse('sucess', 'sucess', $details);
        }

        return $this->displayJson($request, $response, $res_json);
    }

    // 商品の登録
    public function addProduct(Request $request, Response $response, array $args)
    {
        // idの生成
        $next_id = count($this->data) + 1;

        // post queryを取得
        $res_json_str = json_decode(json_encode($request->getParsedBody()), TRUE);

        /* 
            ここでqueryチャックを行う
        */

        // base64データから画像ファイル化
        // base64デコード
        $images_base64 = base64_decode(explode(",", $res_json_str["image"])[1]);

        // ファイル種類から拡張子を取得
        $extension = explode("/", finfo_buffer(finfo_open(), $images_base64, FILEINFO_EXTENSION))[0];
        /* エラーチェックが必要*/

        // ファイル名（id.拡張子）
        $save_file_name = "${next_id}.${extension}";

        // 保存パス
        $res_json_str["image"] = "/images/${save_file_name}";

        // ファイルの保存
        // 成功しなかった場合errorを返す
        if (file_put_contents(__DIR__ . "/../../public/images/${save_file_name}", $images_base64) != False) {
            // post queryにidを追加してDBに保存する
            $res_json_str['id'] = $next_id;
            $insert_data = $this->data;
            $insert_data[$next_id - 1] = $res_json_str;

            $this->prodcut->setData($insert_data);

            $res_json = $this->generateResponse('sucess', 'sucess', $res_json_str);
        } else {
            $res_json = $this->generateResponse('failure', 'error: 画像ファイルを保存できませんでした', '');
        }

        return $this->displayJson($request, $response, $res_json);
    }

    // idの商品を削除
    public function deleteProduct(Request $request, Response $response, array $args)
    {
        // queryからkeyを取得
        $id = (int) $args['id'];

        $data = $this->prodcut->getData();

        $res_json = "";

        // 削除処理
        foreach ($data as $key => $value) {
            if (strcmp($value["id"], $id) == 0) {
                // 削除
                unset($data[$key]);

                // データを更新
                $this->prodcut->setData($data);

                // 削除されたか確認
                if (isset($this->data[$key])) {
                    $res_json = $this->generateResponse('sucess', 'sucess', '');
                } else {
                    $res_json = $this->generateResponse('failure', 'error: 削除に失敗しました', '');
                }
                break;
            }
            $res_json = $this->generateResponse('failure', 'error: 指定されたidの商品はありませんでした', '');
        }




        return $this->displayJson($request, $response, $res_json);
    }
}
