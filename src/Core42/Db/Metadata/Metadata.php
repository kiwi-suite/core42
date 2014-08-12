<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Metadata;

class Metadata extends \Zend\Db\Metadata\Metadata
{

    /**
     * @throws \Exception
     */
    public function refresh()
    {
        $this->source = $this->createSourceFromAdapter($this->adapter);
    }
}
