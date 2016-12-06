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
use Monolog\Handler\SlackHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SlackHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SlackHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['token'])) {
            throw new ServiceNotCreatedException('token option not found for SlackHandler');
        }
        if (empty($options['channel'])) {
            throw new ServiceNotCreatedException('channel option not found for SlackHandler');
        }

        $username = (!empty($options['username'])) ? $options['username'] : 'Core42';
        $useAttachment = (!empty($options['use_attachment'])) ? $options['use_attachment'] : true;
        $iconEmoji = (!empty($options['icon_emoji'])) ? $options['icon_emoji'] : null;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::CRITICAL;
        $useShortAttachment = (!empty($options['use_short_attachment'])) ? $options['use_short_attachment'] : false;
        $includeContextAndExtra = (!empty($options['include_context'])) ? $options['include_context'] : false;

        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new SlackHandler(
            $options['token'],
            $options['channel'],
            $username,
            $useAttachment,
            $iconEmoji,
            $level,
            $bubble,
            $useShortAttachment,
            $includeContextAndExtra
        );
    }
}
