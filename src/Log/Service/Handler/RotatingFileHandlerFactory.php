<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class RotatingFileHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RotatingFileHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['filename'])) {
            throw new ServiceNotCreatedException('filename option not found for StreamHandler');
        }

        $maxFiles = (!empty($options['max_files'])) ? $options['max_files'] : 0;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;
        $filePermission = (!empty($options['file_permission'])) ? $options['file_permission'] : null;
        $userLocking = (!empty($options['use_locking'])) ? $options['use_locking'] : false;

        $handler = new RotatingFileHandler(
            $options['filename'],
            $maxFiles,
            $level,
            $bubble,
            $filePermission,
            $userLocking
        );

        if (!empty($options['filename_format']) && !empty($options['date_format'])) {
            $handler->setFilenameFormat($options['filename_format'], $options['date_format']);
        }

        return $handler;
    }
}
