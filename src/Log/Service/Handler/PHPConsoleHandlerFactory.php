<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2016 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Logger;
use \Zend\ServiceManager\Factory\FactoryInterface;

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
