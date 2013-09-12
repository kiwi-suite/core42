<?php
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
