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

namespace Core42\Stdlib;

class WebcrawlerCheck
{
    protected static $cache = [];

    /**
     * @param string $userAgent
     * @param string $ip
     * @return bool|string
     */
    public static function check($userAgent, $ip)
    {
        if (\array_key_exists($ip, static::$cache)) {
            return static::$cache[$ip];
        }

        $allowedDomains = [];
        $isSearchBot = false;
        $searchBot = false;
        if (\mb_strpos($userAgent, '(compatible; Applebot') !== false) {
            if (\mb_substr($ip, 0, 3) == '17.') {
                return 'Apple';
            }
        } elseif (\mb_strpos($userAgent, 'Mozilla/5.0 (compatible; Yahoo! Slurp') !== false) {
            $allowedDomains = ['yse.yahoo.net'];
            $searchBot = 'Yahoo';
        } elseif (\mb_strpos($userAgent, 'Mozilla/5.0 (compatible; bingbot') !== false) {
            $allowedDomains = ['search.msn.com'];
            $searchBot = 'Bing';
        } elseif (\mb_strpos($userAgent, '(compatible; Googlebot') !== false) {
            $allowedDomains = ['googlebot.com', 'google.com'];
            $searchBot = 'Google';
        }

        if (!empty($allowedDomains)) {
            $hostname = \gethostbyaddr($ip);

            foreach ($allowedDomains as $domain) {
                $length = \mb_strlen($domain);

                if (\mb_substr($hostname, -$length) === $domain) {
                    if (\gethostbyname($hostname) == $ip) {
                        static::$cache[$ip] = $searchBot;
                        $isSearchBot = true;
                    }
                }
            }
        }

        if ($isSearchBot) {
            return $searchBot;
        }

        return false;
    }
}
