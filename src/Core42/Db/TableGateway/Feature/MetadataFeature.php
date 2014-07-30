<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\MetadataFeature as ZendMetadataFeature;
class MetadataFeature extends ZendMetadataFeature
{
    /**
     * @return array
     */
    public function getPrimaryKey()
    {
        $return = $this->sharedData['metadata']['primaryKey'];
        if (!is_array($return)) {
            $return = array($return);
        }

        return $return;
    }
}
