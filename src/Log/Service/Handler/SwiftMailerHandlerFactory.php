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
use Monolog\Handler\BufferHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SwiftMailerHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SwiftMailerHandler|BufferHandler|FingersCrossedHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['transport'])) {
            throw new ServiceNotCreatedException('transport option not found for SwiftMailer');
        }
        if (empty($options['to'])) {
            throw new ServiceNotCreatedException('to option not found for SwiftMailer');
        }

        $transport = $container->get($options['transport']);
        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance();
        if (\is_array($options['to'])) {
            foreach ($options['to'] as $to) {
                $message->addTo($to);
            }
        } else {
            $message->addTo($options['to']);
        }
        if (!empty($options['from'])) {
            $message->addFrom($options['from']);
        }
        if (!empty($options['subject'])) {
            $message->setSubject($options['subject']);
        }

        $level = (!empty($options['level'])) ? $options['level'] : Logger::ERROR;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        $handler = new SwiftMailerHandler($mailer, $message, $level, $bubble);
        if (isset($options['buffer']) && $options['buffer'] === true) {
            return new FingersCrossedHandler($handler, null, 0, true, false);
        }

        return $handler;
    }
}
