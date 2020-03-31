<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Controllers\ProductsController;
use App\Models\Products;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // jsonとか受け取れるようにする
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, true, true);

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    // $app->group('/users', function (Group $group) {
    //     $group->get('', ListUsersAction::class);
    //     $group->get('/{id}', ViewUserAction::class);
    // });


    $app->group('/api/products', function (Group $group) use ($app) {
        // 取得
        $group->get('/{id}', ProductsController::class . ':getProduct');

        // 検索
        $group->get('/', ProductsController::class . ':searchProduct');

        // 全て表示
        $group->get('', ProductsController::class . ':getProductsList');

        // 登録
        $group->post('', ProductsController::class . ':addProduct');

        // 更新
        $group->put('/{id}', ProductsController::class . ':updateProduct');

        // 削除
        $group->delete('/{id}', ProductsController::class . ':deleteProduct');
    });

    // 拡張機能
    $app->group('/statistics', function (Group $group) use ($app) {
        $group->get('/id', function(Request $request, Response $response){
            $api_url = 'http://127.0.0.1:8006';

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $api_url . '/id');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す
            $response_curl = curl_exec($curl);
            $json = json_encode($response_curl, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            $response->getBody()->write($response_curl);
            return $response->withHeader('Content-type', 'application/json');
        });

        $group->get('/info', function(Request $request, Response $response){
            $api_url = 'http://127.0.0.1:8006';

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $api_url . '/info');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す
            $response_curl = curl_exec($curl);
            $json = json_encode($response_curl, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            $response->getBody()->write($response_curl);
            return $response->withHeader('Content-type', 'application/json');
        });
    });

};
