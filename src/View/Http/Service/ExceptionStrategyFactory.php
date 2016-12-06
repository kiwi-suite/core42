<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\View\Http\Service;

use Core42\View\Http\ExceptionStrategy;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Mvc\Service\HttpViewManagerConfigTrait;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ExceptionStrategyFactory implements FactoryInterface
{
    use HttpViewManagerConfigTrait;

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
        $strategy = new ExceptionStrategy();
        $config = $this->getConfig($container);

        $this->injectDisplayExceptions($strategy, $config);
        $this->injectExceptionTemplate($strategy, $config);

        return $strategy;
    }

    /**
     * Inject strategy with configured display_exceptions flag.
     *
     * @param ExceptionStrategy $strategy
     * @param array $config
     */
    private function injectDisplayExceptions(ExceptionStrategy $strategy, array $config)
    {
        $flag = isset($config['display_exceptions']) ? $config['display_exceptions'] : false;
        $strategy->setDisplayExceptions($flag);
    }

    /**
     * Inject strategy with configured exception_template
     *
     * @param ExceptionStrategy $strategy
     * @param array $config
     */
    private function injectExceptionTemplate(ExceptionStrategy $strategy, array $config)
    {
        $template = isset($config['exception_template']) ? $config['exception_template'] : 'error';
        $strategy->setExceptionTemplate($template);
    }
}
