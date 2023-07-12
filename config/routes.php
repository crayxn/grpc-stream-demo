<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

\Crayoon\HyperfGrpc\GrpcHelper::RegisterRoutes(function (){
    //demo
    Router::addGroup("/demo.DemoSrv", function () {
        Router::post("/unary", [\App\Service\DemoSrv::class, 'unary']);
        Router::post("/serverStreaming", [\App\Service\DemoSrv::class, 'serverStreaming']);
        Router::post("/clientStreaming", [\App\Service\DemoSrv::class, 'clientStreaming']);
        Router::post("/bidirectional", [\App\Service\DemoSrv::class, 'bidirectional']);
    });
},'grpc',[],true);
