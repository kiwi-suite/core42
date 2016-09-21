<?php
namespace Core42\Db\TableGateway\Service;

use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Hydrator\BaseHydrator;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class TableGatewayFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = $container->get('Db\Master');
        $slave = null;
        if ($container->has('Db\Slave')) {
            $slave =  $container->get('Db\Slave');
        }

        $hydrator = $container->get('HydratorManager')->get(BaseHydrator::class);

        /** @var AbstractTableGateway $gateway */
        $gateway = new $requestedName($adapter, $hydrator, $slave);

        $gateway->initialize();

        return $gateway;
    }
}
