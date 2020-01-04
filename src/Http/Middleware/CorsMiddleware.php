<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: minms
 * Date: 2019/5/24
 * Time: 17:46
 */

namespace Minms\SwoftContracts\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use Swoft\Http\Server\Contract\MiddlewareInterface;

/**
 * @Bean("cors")
 */
class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ('OPTIONS' === $request->getMethod()) {
            $response = Context::get()->getResponse();
            return self::configResponse($response);
        }

        $path = $request->getUri()->getPath();
        if ($path === '/favicon.ico') {
            $response = Context::get()->getResponse();
            return $response->withStatus(200);
        }

        return self::configResponse(
            $handler->handle($request)
        );
    }

    public static function configResponse(ResponseInterface $response)
    {
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Origin', '*')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
    }

}
