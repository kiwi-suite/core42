<?php
namespace Core42\Db\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\Db\Adapter\Adapter;

class MasterServiceFactory implements FactoryInterface
{

    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new Adapter($config['db_master']);
    }
}
