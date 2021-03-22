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

class Department extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    /**
     * @var string
     */
    protected $name = 'Department';

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

    public function getDepartments($pageToken = '', $parentDepartmentId = 0)
    {
        $params = [
            'fetch_child' => true,
            'parent_department_id' => $parentDepartmentId,
            'page_size' => 50,
        ];
        if (! empty($pageToken)) {
            $params['page_token'] = $pageToken;
        }

        $ret = $this->client()->get('/open-apis/contact/v3/departments?' . http_build_query($params), [
            RequestOptions::HEADERS => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ],
        ]);
        $ret = $this->handleResponse($ret)['data'] ?? [];
        $departments = $ret['items'];
        if (! empty($ret) && $ret['has_more'] && ! empty($ret['page_token'])) {
            $deps = collect($this->getDepartments($ret['page_token']));
            if ($deps->isNotEmpty()) {
                $deps->each(function ($item) use (&$departments) {
                    $departments[] = $item;
                });
            }
        }
        return $departments;
    }
}
