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

class Users extends AbstractProvider
{
    /**
     * @var string
     */
    protected $name = 'Users';

    /**
     * @var User
     */
    private $users;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        foreach ($this->config->get('feishu.users', []) as $key => $item) {
            $this->users[$key] = make(User::class, [
                'conf' => $item,
            ]);
        }
    }

    public function __get($name)
    {
        if (! isset($this->users[$name]) || ! $this->users[$name] instanceof User) {
            throw new InvalidArgumentException("User {$name} is invalid.");
        }

        return $this->users[$name];
    }
}
