<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42\Db\Transaction\Service;

use Core42\Db\Transaction\TransactionManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TransactionManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TransactionManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['db']['adapters'];
        $adapterNames = \array_keys($config);

        $adapters = [];
        foreach ($adapterNames as $adapterName) {
            $adapters[] = $container->get($adapterName);
        }

        return new TransactionManager($adapters);
    }
}
