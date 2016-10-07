<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway\Service;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class TableGatewayPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = AbstractTableGateway::class;

    /**
     * @param string $name
     * @param array|null $options
     * @return mixed
     */
    public function get($name, array $options = null)
    {
        if (!$this->has($name)) {
            if (!$this->autoAddInvokableClass || !class_exists($name)) {
                throw new ServiceNotFoundException(sprintf(
                    'A plugin by the name "%s" was not found in the plugin manager %s',
                    $name,
                    get_class($this)
                ));
            }

            $this->setFactory($name, TableGatewayFactory::class);
        }
        return parent::get($name, $options);
    }
}
