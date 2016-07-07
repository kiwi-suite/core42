<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator\Strategy\Service;

use Core42\Hydrator\Strategy\Database\DatabaseStrategyInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class HydratorStrategyPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = DatabaseStrategyInterface::class;

    /**
     * @param string|\Zend\ServiceManager\Factory\AbstractFactoryInterface $factory
     * @throws \Exception
     */
    public function addAbstractFactory($factory)
    {
        throw new \Exception(
            'Abstract factories are not allowed in hydrator strategy plugin manager'
        );
    }

    /**
     * @return array
     */
    public function getServiceAliases()
    {
        return array_keys($this->aliases);
    }
}
