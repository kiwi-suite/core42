<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Metadata;

use Core42\Db\Metadata\Source\CacheSource;
use Zend\Cache\Storage\StorageInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Source\Factory;

class CacheMetadata extends Metadata
{

    /**
     * @type StorageInterface
     */
    protected $storage;

    /**
     * Constructor
     *
     * @param Adapter $adapter
     * @param StorageInterface $storage
     */
    public function __construct(Adapter $adapter, $storage = null)
    {
        $this->adapter = $adapter;
        $this->storage = $storage;
        $this->source = Factory::createSourceFromAdapter($adapter);

        if ($storage !== null) {
            new CacheSource($this->source, $storage);
        }
    }
}
