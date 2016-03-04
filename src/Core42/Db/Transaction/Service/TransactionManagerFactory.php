<?php
namespace Core42\Db\Transaction\Service;

use Core42\Db\Transaction\TransactionManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TransactionManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['db']['adapters'];
        $adapterNames = array_keys($config);

        $adapters = [];
        foreach ($adapterNames as $adapterName) {
            $adapters[] = $container->get($adapterName);
        }

        return new TransactionManager($adapters);
    }
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, TransactionManager::class);
    }
}
