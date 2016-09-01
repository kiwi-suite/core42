<?php
namespace Core42\Form\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Form\Factory;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Stdlib\InitializableInterface;

class FormFactory implements FactoryInterface
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
        $form = new $requestedName();
        $form->setFormFactory(new Factory($container->get('FormElementManager')));

        if ($form instanceof InitializableInterface) {
            $form->init();
        }

        return $form;
    }
}
