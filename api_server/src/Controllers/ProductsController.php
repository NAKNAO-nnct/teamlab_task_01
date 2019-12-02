<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use phpDocumentor\Reflection\Types\Null_;
use TheSeer\Tokenizer\Exception;

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

    // 商品を検索
    public function searchProduct(Request $request, Response $response, array $args)
    {
        $get_query = $request->getQueryParams();
        if (count($get_query) == 0) {
            return $this->getProductsList($request, $response, $args);
        }

        // 該当データ
        $details = $this->db_access_conector->searchData($get_query);

        if (empty($details)) {
            $res_json = $this->generateResponse('failure', 'error: No such product', '');
        } else {
            $res_json = $this->generateResponse('sucess', 'sucess', $details);
        }

        return $this->displayJson($request, $response, $res_json);
    }

    // 商品リストを取得
    public function getProductsList(Request $request, Response $response, array $args)
    {
        $res_json = $this->generateResponse('sccess', 'success', $this->db_access_conector->getAllData());

        return $this->displayJson($request, $response, $res_json);
    }

    // 商品を取得
    public function getProduct(Request $request, Response $response, array $args)
    {
        // get queryのkeyを取得
        $keys = (int) $args['id'];
        $details = [];

        $details[0] = $this->getProductFromId([$keys])['value'];

        if ($details[0][0] == "") {
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
        $next_id = count($this->db_access_conector->getAllData()) + 1;

        // post queryを取得
        $res_json_str = json_decode(json_encode($request->getParsedBody()), TRUE);

        try {
            // queryチェック
            if (!$this->queryCheck(array('type' => 'register', 'value' => $res_json_str)))
                throw new Exception('パラメータが不十分です．');

            // 画像を保存
            $res_save_image = $this->saveProductImages($res_json_str["image"], $next_id);

            // ファイルの保存
            // 成功しなかった場合errorを返す
            if ($res_save_image['ok'] != False) {

                // 保存パス
                $res_json_str["image"] = "/images/" . $res_save_image['save_path'];

                // dbに追加
                $res_json_str['id'] = $next_id;
                $this->db_access_conector->insertQuery($res_json_str);

                $res_json = $this->generateResponse('sucess', 'sucess', $res_json_str);
            } else
                throw new Exception('画像ファイルを保存できませんでした');
        } catch (Exception $e) {
            $res_json = $this->generateResponse('failure', 'error: ' . $e->getMessage(), '');
        }


        return $this->displayJson($request, $response, $res_json);
    }

    // idの商品の情報を更新
    public function updateProduct(Request $request, Response $response, array $args)
    {
        // queryからidを取得
        $id = (int) $args['id'];

        // get request body
        $res_json_str = json_decode(json_encode($request->getParsedBody()), TRUE);

        /* 
            query check
        */
        try {
            // queryチェック
            if (!$this->queryCheck(array('type' => 'register', 'value' => $res_json_str)))
                throw new Exception('パラメータが不十分です．');
            // 変更元データの取得
            $old_data = $this->getProductFromId([$id]);

            // idの商品の有無
            if ($old_data['value'][0] == "")
                throw new Exception('No such product_id');
            else {
                // 更新したいデータのみ送られてくる
                foreach ($res_json_str as $key => $value) {
                    if ($value != "") {
                        // 画像を更新する場合，元の画像を削除
                        if (strcmp($key, 'image') == 0) {
                            if (strcmp($this->deleteProductImages($old_data['value'][0]['image'])['ok'], 'success') != 0) {
                                // $res_json = $this->generateResponse('failure', 'error: image file error', '');
                                continue;
                            }
                            // 画像を保存
                            $res_save_image = $this->saveProductImages($value, $id);
                            if (!$res_save_image['ok']) {
                                $old_data['value'][0][$key] = $res_save_image['save_path'];
                                continue;
                            } else
                                throw new Exception('ファイルを保存できませんでした');
                        }

                        // 情報を上書き
                        $old_data['value'][0][$key] = $value;
                    }
                }

                // データを更新
                $this->db_access_conector->updateQuery($old_data['value'][0]);

                $res_json = $this->generateResponse('sucess', 'sucess', $old_data['value']);
            }
        } catch (Exception $e) {
            $res_json = $this->generateResponse('failure', 'error: ' . $e->getMessage(), '');
        }


        // var_dump($old_data);
        return $this->displayJson($request, $response, $res_json);
    }

    // idの商品を削除
    public function deleteProduct(Request $request, Response $response, array $args)
    {
        // queryからkeyを取得
        $id = (int) $args['id'];

        $data = $this->db_access_conector->getDataFromIds([$id]);

        $res_json = "";

        // 削除処理
        if (empty($data))
            $res_json = $this->generateResponse('failure', 'error: 指定されたidの商品はありませんでした', '');
        else
            $res_json = $this->generateResponse('sucess', 'sucess', '');

        // 画像ファイルの削除
        if ($this->deleteProductImages($data['image'])['ok'] != 'success') {
            $res_json = $this->generateResponse('failure', 'error: 削除に失敗しました', '');
        }

        return $this->displayJson($request, $response, $res_json);
    }

    // queryチェック
    public function queryCheck($query)
    {
        // query flag
        $query_param_flag = 1;

        // 商品タイトルがある場合
        if (!empty($query['value']['name'])) {
            // 商品タイトルは100文字いないか
            if (count($query['value']['name'] > 100))
                return False;
            $query_param_flag *= 3;
        }

        // 説明文がある場合
        if (!empty($query['value']['description'])) {
            // 説明文は500文字いないか
            if (count($query['value']['description'] > 500))
                return False;
            $query_param_flag *= 5;
        }

        // 価格がある場合
        if (!empty($query['value']['price'])) {
            // 価格は0円以上か
            if (count($query['value']['price'] >= 0))
                return False;
            $query_param_flag *= 7;
        }

        // imageがある場合
        if (!empty($query['value']['image']))
            $query_param_flag += 11;


        switch ($query['type']) {
            case "resgister":
                if (!$query_param_flag % 1155 == 0)
                    return false;
                break;
        }

        return True;
    }

    // idから商品を取得する
    public function getProductFromId($ids)
    {
        $data = $this->db_access_conector->getDataFromIds($ids);

        $res_data = [];

        // idの商品を取得
        foreach ($ids as $id) {
            foreach ($data as $key => $value) {
                if (strcmp($value["id"], $id) == 0) {
                    array_push($res_data, $value);
                }
            }
        }
        if (count($res_data) != 0) {
            return array('value' => $res_data, 'key' => $key);
        }
        return;
    }

    // 画像を削除
    public function deleteProductImages($file_name)
    {
        $file_path = __DIR__ . "/../../public/" . $file_name;

        // ファイルの有無を確認
        if (file_exists($file_path) == False) {
            return array('ok' => 'success', 'message' => 'file not found');
        }
        // ファイルを削除
        if (unlink($file_path))
            return array('ok' => 'success', 'message' => '');
        return array('ok' => 'failure', 'message' => '削除に失敗しました');
    }

    // 画像を保存
    public function saveProductImages($images_base64, $id)
    {
        // base64データから画像ファイル化
        // base64デコード
        $images_base64 = base64_decode(explode(",", $images_base64)[1]);

        // ファイル種類から拡張子を取得
        $extension = explode("/", finfo_buffer(finfo_open(), $images_base64, FILEINFO_EXTENSION))[0];
        /* エラーチェックが必要*/

        // ファイル名（id.拡張子）
        $save_file_name = "${id}.${extension}";

        // ファイルの保存
        // 成功しなかった場合errorを返す
        if (file_put_contents(__DIR__ . "/../../public/images/${save_file_name}", $images_base64)) {
            return array('ok' => True, 'save_path' => "/images/${save_file_name}");
        }
        return array('ok' => False);
    }

    // 商品のidリストを取得する
    public function getProductId()
    {
        $products = $this->db_access_conector->getAllData();
        $products_id = [];

        foreach ($products as  $value) {
            array_push($products_id, (int) $value['id']);
        }

        return $products_id;
    }
}
