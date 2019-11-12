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
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    // $app->group('/users', function (Group $group) {
    //     $group->get('', ListUsersAction::class);
    //     $group->get('/{id}', ViewUserAction::class);
    // });

    $app->group('/api/products', function (Group $group) {
        // 取得
        $group->get('/{id}', ProductsController::class . ':getProduct');

        // 検索
        $group->get('', ProductsController::class . ':getProductsList');

        // 登録
        $group->post('', ProductsController::class . ':getProductsList');
        $group->post('/{id}', function (Request $request, Response $response, array $args) { });

        // 更新
        $group->put('/{id}', function (Request $request, Response $response, $id) { });

        // 削除
        $group->delete('/{id}', function (Request $request, Response $response, $id) { });
    });

    $app->get('/images/{name}', function (Response $response, $name) {
        return $response->withHeader('Content-type', 'image/' . $type);
    });
};
