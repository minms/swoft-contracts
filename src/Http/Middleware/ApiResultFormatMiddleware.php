<?php declare(strict_types=1);


namespace Minms\SwoftContracts\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\MiddlewareInterface;

/**
 * Class ApiResultFormatMiddleware
 * @package Minms\SwoftContracts\Http\Middleware
 *
 * @Bean("apiResultFormat")
 */
class ApiResultFormatMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Response $response */
        $response = $handler->handle($request);

        return $response->withData(
            $this->format($response->getData(), 0)
        )->withContentType('application/json');
    }

    /**
     * 格式化输出
     * @param      $data
     * @param int  $code
     * @param null $message
     * @return array
     */
    public function format($data, $code = 0, $message = null)
    {
        $result = [
            'code' => (int)$code,
        ];

        if (!is_null($data)) $result['data'] = $data;
        if (!is_null($message)) $result['message'] = $message;

        return $result;
    }
}