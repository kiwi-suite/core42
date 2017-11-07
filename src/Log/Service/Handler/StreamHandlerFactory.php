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

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class StreamHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return StreamHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['stream'])) {
            throw new ServiceNotCreatedException('stream option not found for StreamHandler');
        }

        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;
        $filePermission = (!empty($options['file_permission'])) ? $options['file_permission'] : null;
        $userLocking = (!empty($options['use_locking'])) ? $options['use_locking'] : false;

        return new StreamHandler($options['stream'], $level, $bubble, $filePermission, $userLocking);
    }
}
