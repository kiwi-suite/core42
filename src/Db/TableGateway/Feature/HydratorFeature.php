<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Hydrator\AbstractHydrator;

class HydratorFeature extends AbstractFeature
{
    /**
     * @var AbstractPluginManager
     */
    protected $hydratorStrategyPluginManager;

    /**
     *
     * @param AbstractPluginManager $hydratorStrategyPluginManager
     */
    public function __construct(
        AbstractPluginManager $hydratorStrategyPluginManager
    ) {
        $this->hydratorStrategyPluginManager = $hydratorStrategyPluginManager;
    }

    /**
     *
     */
    public function postInitialize()
    {
        /* @var AbstractHydrator $hydrator */
        $hydrator = $this->tableGateway->getHydrator();

        if (!$this->tableGateway instanceof \Core42\Db\TableGateway\AbstractTableGateway) {
            throw new \Exception('HydratorFeature must only be used in Core42 TableGateways');
        }

        $columns = $this->tableGateway->getDatabaseTypeMap();

        foreach ($columns as $columnName => $strategy) {
            $strategy = $this->hydratorStrategyPluginManager->get(
                $strategy
            );
            $hydrator->addStrategy($columnName, $strategy);
        }
    }
}
