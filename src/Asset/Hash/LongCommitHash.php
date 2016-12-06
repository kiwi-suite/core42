<?php
namespace Core42\Asset\Hash;

use Zend\Json\Json;

class LongCommitHash implements CommitHashInterface
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

        if (empty($revision['revision_hash'])) {
            return "";
        }

        return rawurlencode($revision['revision_hash']);
    }
}
