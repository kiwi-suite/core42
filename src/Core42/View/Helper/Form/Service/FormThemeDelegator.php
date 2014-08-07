<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper\Form\Service;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\HelperInterface;
use Zend\View\Renderer\RendererInterface as Renderer;

class FormThemeDelegator implements DelegatorFactoryInterface, HelperInterface
{

    /**
     * A factory that creates delegates of a given service
     *
     * @param ServiceLocatorInterface $serviceLocator the service locator which requested the service
     * @param string $name the normalized service name
     * @param string $requestedName the requested service name
     * @param callable $callback the callback that is responsible for creating the service
     *
     * @return mixed
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        $viewHelper = $callback();

        $viewHelper->setThemeManager($serviceLocator->getServiceLocator()->get('Core42\Form\ThemeManager'));

        return $viewHelper;
    }

    /**
     * Set the View object
     *
     * @param  Renderer $view
     * @return HelperInterface
     */
    public function setView(Renderer $view)
    {
        // TODO: Implement setView() method.
    }

    /**
     * Get the View object
     *
     * @return Renderer
     */
    public function getView()
    {
        // TODO: Implement getView() method.
    }
}
