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


namespace Core42\Console;

use Zend\Console\ColorInterface;

class Console extends \Zend\Console\Console
{
    /**
     * @param string $message
     * @return string
     */
    public static function outputFilter($message)
    {
        $message = \preg_replace_callback(
            '#(\\\\?)<(/?)([a-z][a-z0-9_=;-]*)?>((?: [^<\\\\]+ | (?!<(?:/?[a-z]|/>)). | .(?<=\\\\<) )*)#isx',
            function ($matches) {
                if ($matches[2] == '/') {
                    return $matches[4];
                }

                switch ($matches[3]) {
                    case 'error':
                        return Console::getInstance()->colorize(
                            $matches[4],
                            ColorInterface::WHITE,
                            ColorInterface::RED
                        );
                    case 'info':
                        return Console::getInstance()->colorize(
                            $matches[4],
                            ColorInterface::GREEN
                        );
                    case 'warning':
                        return Console::getInstance()->colorize(
                            $matches[4],
                            ColorInterface::YELLOW
                        );
                    case 'question':
                        return Console::getInstance()->colorize(
                            $matches[4],
                            ColorInterface::BLACK,
                            ColorInterface::CYAN
                        );
                    default:
                        return $matches[4];
                }
            },
            $message
        );

        return $message;
    }
}
