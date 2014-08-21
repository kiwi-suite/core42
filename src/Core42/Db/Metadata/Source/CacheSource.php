<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Metadata\Source;

use Zend\Cache\Storage\StorageInterface;
use Zend\Db\Metadata\Source\AbstractSource;

class CacheSource extends AbstractSource
{
    const CACHE_KEY = 'db_metadata';

    /**
     * Constructor
     *
     * @param AbstractSource $source
     * @param StorageInterface $storage
     */
    public function __construct(AbstractSource $source, StorageInterface $storage)
    {
        if ($storage->hasItem(self::CACHE_KEY)) {
            $source->data = $storage->getItem(self::CACHE_KEY);
        } else {

            $source->loadSchemaData();

            $tableNames = $source->getTableNames($source->defaultSchema);
            foreach($tableNames as $table) {

                $source->loadColumnData($table, $source->defaultSchema);
                $source->loadConstraintData($table, $source->defaultSchema);
                $source->loadConstraintReferences($table, $source->defaultSchema);
            }

            $source->loadConstraintDataKeys($source->defaultSchema);
            $source->loadTriggerData($source->defaultSchema);

            $storage->setItem(self::CACHE_KEY, $source->data);
        }
    }
}
