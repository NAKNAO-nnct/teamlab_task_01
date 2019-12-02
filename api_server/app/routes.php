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

        // 登録
        $group->post('', ProductsController::class . ':addProduct');

        // 更新
        $group->put('/{id}', ProductsController::class . ':updateProduct');

        // 削除
        $group->delete('/{id}', ProductsController::class . ':deleteProduct');
    });
};
