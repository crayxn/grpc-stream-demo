<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace App\Service;

use App\Grpc\Demo\DemoSrv\Reply;
use App\Grpc\Demo\DemoSrv\Req;
use Crayoon\HyperfGrpc\Server\Handler\StreamHandler;
use Crayoon\HyperfGrpc\Server\Http2Frame\Http2Frame;
use Hyperf\Context\Context;
use Hyperf\Grpc\Parser;
use Psr\Http\Message\ServerRequestInterface;

class DemoSrv
{
    private function handler():StreamHandler
    {
        return Context::get(StreamHandler::class);
    }

    public function unary(): void
    {
        $req = $this->handler()->receive(Req::class);
        $reply = new Reply();
        // do something
        $reply->setMessage($req->getMessage() . ";reply" . time());
        // call
        $this->handler()->push($reply);
    }

    public function serverStreaming(): void
    {
        $handler = $this->handler();
        $req = $handler->receive(Req::class);
        $reply = new Reply();
        // do something
        $reply->setMessage($req->getMessage() . ";reply" . time());
        // call
        $handler->push($reply);
        sleep(1);
        $reply->setMessage($req->getMessage() . ";reply" . time());
        $handler->push($reply);
        sleep(1);
        $reply->setMessage($req->getMessage() . ";reply" . time());
        $handler->push($reply);
    }

    public function clientStreaming(): void
    {
        $handler = $this->handler();
        $reply = new Reply();
        $content = 'request ';
        /**
         * @var Req $req
         */
        while (Http2Frame::EOF !== $req = $handler->receive(Req::class)) {
            // do something
            $content .= $req->getMessage() . ';';
        }
        $reply->setMessage($content. 'reply');
        $handler->push($reply);
    }

    public function bidirectional(): void
    {
        /**
         * @var Req $req
         */
        while (Http2Frame::EOF !== $req = $this->handler()->receive(Req::class)) {
            // do something
            $reply = new Reply();
            $reply->setMessage($req->getMessage() . ";reply");
            $this->handler()->push($reply);
        }
    }
}