<?php
namespace Core42\Asset\Hash;

use Zend\Json\Json;

class DefaultCommitHash implements CommitHashInterface
{

    public function getHash()
    {
        if (!file_exists('resources/version/revision.json')) {
            return "";
        }

        try {
            $revision = Json::decode(file_get_contents('resources/version/revision.json'), Json::TYPE_ARRAY);
        } catch (\Exception $e) {
            return "";
        }

        if (empty($revision['revision_hash_short'])) {
            return "";
        }

        return  "v-" . rawurlencode($revision['revision_hash_short']);
    }
}
