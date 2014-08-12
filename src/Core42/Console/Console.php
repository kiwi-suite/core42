<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Console;

use Zend\Console\ColorInterface;

class Console extends \Zend\Console\Console
{
    /**
     * @param string $message
     */
    public static function outputFilter($message)
    {
        $message = preg_replace_callback(
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
                    case 'comment':
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

        Console::getInstance()->writeLine($message);
    }
}
