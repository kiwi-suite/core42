<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator\Strategy\Service;

use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\ServiceManager\AbstractPluginManager;

class StrategyPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = StrategyInterface::class;
}
