<?php

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;

use Monolog\Handler\SyslogHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use \Zend\ServiceManager\Factory\FactoryInterface;

class SyslogUdpFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SyslogUdpFactory
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
