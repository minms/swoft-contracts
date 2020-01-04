<?php declare(strict_types=1);

use Swoft\Db\Eloquent\Builder;
use Minms\SwoftContracts\Beans\Pagination;

/**
 * 获取分页结果
 * @param Builder $builder
 * @param array   $columns
 * @return array
 * @throws \Swoft\Db\Exception\DbException
 */
function paginate(Builder $builder, array $columns = ['*'])
{
    $pager = Pagination::new((clone $builder)->count());
    $items = $builder->forPage(
        $pager->getPage(),
        $pager->getPageSize()
    )->get($columns);

    return [
        'items' => $items,
        'pager' => $pager->toArray()
    ];
}

/**
 * 获取加密后的密码
 * @param string $password
 * @return string
 */
function gen_password(string $password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * 验证密码是否正确
 * @param string $password
 * @param string $password_hash
 * @return bool
 */
function verify_password(string $password, string $password_hash)
{
    return password_verify($password, $password_hash);
}


/**
 * 获取jwt生成后的字符串
 * @param array  $params
 * @param string $key
 * @return string
 */
function jwt_gen_token(array $params, $key = 'SIGN_KEY')
{
    $signer  = new \Lcobucci\JWT\Signer\Hmac\Sha256();
    $builder = (new \Lcobucci\JWT\Builder())->issuedAt(time());
    foreach ($params as $k => $value) {
        $builder->withClaim($k, $value);
    }

    $key = new \Lcobucci\JWT\Signer\Key($key);
    return $builder->getToken($signer, $key)->__toString();
}

/**
 * 获取客户端TOKEN字符串解码
 * @param string $token
 * @param string $key
 * @return array
 */
function jwt_get_claims(string $token, $key = 'SIGN_KEY')
{
    $token  = (new \Lcobucci\JWT\Parser())->parse($token);
    $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
    if (!$token->verify($signer, $key)) {
        return false;
    }
    $params = [];
    foreach ($token->getClaims() as $claim => $value) {
        $params[$claim] = $token->getClaim($claim);
    }

    return $params;
}

/**
 * 获取客户端传入的TOKEN
 * @return array|mixed|null|string
 */
function client_token($headerPrefix = 'Bear', $postKey = 'token')
{
    $request = Swoft\Context\Context::get()->getRequest();
    $token   = $request->getHeaderLine('authorization');
    if (!empty($token)) {
        return str_replace($headerPrefix . ' ', '', $token);
    }

    $token = $request->get($postKey);
    if (!empty($token)) {
        return $token;
    }

    $token = $request->post($postKey);
    if (!empty($token)) {
        return $token;
    }

    return null;
}