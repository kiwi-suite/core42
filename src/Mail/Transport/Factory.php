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

namespace Core42\Mail\Transport;

class Factory
{
    /**
     * @param $config
     * @return \Swift_NullTransport
     */
    public static function create($config)
    {
        if (!isset($config['type'])) {
            $config = [
                'type' => 'null',
                'options' => [],
            ];
        }
        if (!isset($config['options'])) {
            $config['options'] = [];
        }

        switch ($config['type']) {
            case 'smtp':
                $transport = self::createSmtpTransport($config['options']);
                break;
            case 'sendmail':
                $transport = self::createSendmailTransport($config['options']);
                break;
            case 'mail':
                $transport = self::createMailTransport($config['options']);
                break;
            case 'null':
            default:
                $transport = self::createNullTransport($config['options']);
                break;
        }

        return $transport;
    }

    /**
     * @param $options
     * @return \Swift_NullTransport
     */
    protected static function createNullTransport($options)
    {
        return \Swift_NullTransport::newInstance();
    }

    /**
     * @param $options
     * @return \Swift_MailTransport
     */
    protected static function createMailTransport($options)
    {
        $extraParams = (isset($options['extra'])) ? $options['extra'] : null;

        return \Swift_MailTransport::newInstance($extraParams);
    }

    /**
     * @param $options
     * @return \Swift_SendmailTransport
     */
    protected static function createSendmailTransport($options)
    {
        $command = (isset($options['command'])) ? $options['command'] : null;

        return \Swift_SendmailTransport::newInstance($command);
    }

    /**
     * @param $options
     * @return \Swift_MailTransport
     */
    protected static function createSmtpTransport($options)
    {
        $host = (isset($options['host'])) ? $options['host'] : null;
        $port = (isset($options['port'])) ? $options['port'] : null;
        $encryption = (isset($options['encryption'])) ? $options['encryption'] : null;
        $transport = \Swift_SmtpTransport::newInstance($host, $port, $encryption);

        if (isset($options['username'])) {
            $transport->setUsername($options['username']);
        }
        if (isset($options['password'])) {
            $transport->setPassword($options['password']);
        }
        if (isset($options['auth_mode'])) {
            $transport->setAuthMode($options['auth_mode']);
        }

        return $transport;
    }
}
