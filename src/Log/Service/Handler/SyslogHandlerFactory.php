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
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SyslogHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SyslogHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['ident'])) {
            throw new ServiceNotCreatedException('ident option not found for SyslogHandler');
        }

        $facility = (!empty($options['facility'])) ? $options['facility'] : LOG_USER;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;
        $logopts = (!empty($options['logopts'])) ? $options['logopts'] : LOG_PID;

        return new SyslogHandler($options['ident'], $facility, $level, $bubble, $logopts);
    }
}
