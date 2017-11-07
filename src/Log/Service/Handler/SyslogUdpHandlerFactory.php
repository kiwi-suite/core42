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
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SyslogUdpHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SyslogUdpHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['host'])) {
            throw new ServiceNotCreatedException('host option not found for SyslogUdpHandler');
        }

        $port = (!empty($options['port'])) ? $options['port'] : 514;
        $facility = (!empty($options['facility'])) ? $options['facility'] : LOG_USER;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new SyslogUdpHandler($options['host'], $port, $facility, $level, $bubble);
    }
}
