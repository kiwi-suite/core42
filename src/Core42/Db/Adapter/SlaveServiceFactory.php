<?php
namespace Core42\Db\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\Db\Adapter\Adapter;

class SlaveServiceFactory implements FactoryInterface
{

    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        if ($config['db_slave'] === false) {
            $adapter = $serviceLocator->get("db_master");
        } else {
            $adapter = new Adapter($config['db_slave']);
        }
        return $adapter;
    }
}
