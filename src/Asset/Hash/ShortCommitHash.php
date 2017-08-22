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

namespace Core42\Asset\Hash;

use Zend\Json\Json;

class ShortCommitHash implements CommitHashInterface
{
    public function getHash()
    {
        if (!\file_exists('resources/version/revision.json')) {
            return "";
        }

        try {
            $revision = Json::decode(\file_get_contents('resources/version/revision.json'), Json::TYPE_ARRAY);
        } catch (\Exception $e) {
            return "";
        }

        if (empty($revision['revision_hash_short'])) {
            return "";
        }

        return \rawurlencode($revision['revision_hash_short']);
    }
}
