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
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;

class PHPConsoleHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PHPConsoleHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = (!empty($options['options'])) ? $options['options'] : [];
        $connector = (!empty($options['connector'])) ? $options['connector'] : null;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new PHPConsoleHandler($options, $connector, $level, $bubble);
    }
}
