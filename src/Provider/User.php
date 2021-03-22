<?php

declare(strict_types=1);
/**
 * This file is part of Stars <Starlink User Center (SSO)>.
 * Developed By Sakura Backend Team Of Starlinke
 *
 * @link     https://www.starlinke.com
 * @document https://starlink.feishu.cn/docs/doccn3jY4If0LrKByUrZvlusX0B
 * $contact  simon-fu@starlinke.com
 */
namespace HyperfX\Feishu\Provider;

use GuzzleHttp\RequestOptions;
use HyperfX\Feishu\AbstractProvider;
use HyperfX\Feishu\TenantAccessTokenNeeded;
use Psr\Container\ContainerInterface;

class User extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    /**
     * @var string
     */
    protected $name = 'User';

    /**
     * @var array
     */
    private $conf;

    public function __construct(ContainerInterface $container, array $conf)
    {
        parent::__construct($container);
        $this->conf = $conf;
        $this->init($conf['app_id'], $conf['app_secret']);
    }

    /**
     * 基于飞书扫码之后生成的Code，获取用户信息
     * @param $code
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserInfo($code)
    {
        $userAccessToken = $this->getUserAccessToken($code);
        $ret = $this->client()->get("/open-apis/contact/v3/users/{$userAccessToken['open_id']}", [
            RequestOptions::HEADERS => [
                'content-type' => 'application/json',
                'Authorization' => $userAccessToken['token_type'] . ' ' . $userAccessToken['access_token'],
            ],
        ]);
        return $this->handleResponse($ret)['data']['user'];
    }

    /**
     * 基于Code 获取用户获取信息所需要的access_token
     * @param $code
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getUserAccessToken($code)
    {
        $ret = $this->client()->post('/open-apis/authen/v1/access_token', [
            RequestOptions::HEADERS => [
                'content-type' => 'application/json',
            ],
            RequestOptions::JSON => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'app_access_token' => $this->getAccessToken(),
            ],
        ]);
        return $this->handleResponse($ret)['data'];
    }
}
