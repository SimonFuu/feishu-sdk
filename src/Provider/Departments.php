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

use HyperfX\Feishu\AbstractProvider;
use HyperfX\Feishu\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

class Departments extends AbstractProvider
{
    /**
     * @var string
     */
    protected $name = 'Departments';

    /**
     * @var Department
     */
    private $departments;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        foreach ($this->config->get('feishu.departments', []) as $key => $item) {
            $this->departments[$key] = make(Department::class, [
                'conf' => $item,
            ]);
        }
    }

    public function __get($name)
    {
        if (! isset($this->departments[$name]) || ! $this->departments[$name] instanceof Department) {
            throw new InvalidArgumentException("Department {$name} is invalid.");
        }

        return $this->departments[$name];
    }
}
