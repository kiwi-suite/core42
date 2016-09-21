<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Form\Service;


use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormFactoryAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

class FormElementManager extends FormElementManagerV3Polyfill
{
    protected $aliases = [];

    /**
     * Factories for default set of helpers
     *
     * @var array
     */
    protected $factories = [];

    /**
     * @param string $name
     * @param null $class
     */
    public function setInvokableClass($name, $class = null)
    {
        $class = $class ?: $name;

        if (! $this->has($class)) {
            $this->setFactory($class, ElementFactory::class);
        }

        if ($class === $name) {
            return;
        }

        $this->setAlias($name, $class);
    }

    /**
     * @param string $name
     * @param array $options
     * @param bool $usePeeringServiceManagers
     * @return object
     */
    public function get($name, $options = [], $usePeeringServiceManagers = true)
    {
        if (! $this->has($name)) {
            if (! $this->autoAddInvokableClass || ! class_exists($name)) {
                throw new ServiceNotCreatedException(sprintf(
                    'A plugin by the name "%s" was not found in the plugin manager %s',
                    $name,
                    get_class($this)
                ));
            }

            $this->setInvokableClass($name);
        }
        return parent::get($name, $options, $usePeeringServiceManagers);
    }


    /**
     * @param ContainerInterface $container
     * @param mixed $instance
     */
    public function injectFactory(ContainerInterface $container, $instance)
    {
        if (! $instance instanceof FormFactoryAwareInterface) {
            return;
        }

        $factory = new Factory();
        $instance->setFormFactory($factory);
        $factory->setFormElementManager($this);

        if ($container && $container->has('InputFilterManager')) {
            $inputFilters = $container->get('InputFilterManager');
            $factory->getInputFilterFactory()->setInputFilterManager($inputFilters);
        }
    }
}
