<?php
namespace Core42\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class ValueManager extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var \Core42\ValueManager\ValueManager
     */
    private $valueManager;

    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var null|array
     */
    private $partialMapping = array(
        '*' => 'partial/value-manager/input',
    );

    /**
     * @return \Core42\View\Helper\ValueManager
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param  \Core42\ValueManager\ValueManager $valueManager
     * @return \Core42\View\Helper\ValueManager
     */
    public function setValueManager(\Core42\ValueManager\ValueManager $valueManager)
    {
        $this->valueManager = $valueManager;

        return $this;
    }

    /**
     * @param string $name
     * @param string $partial
     * @param array $options
     * @return mixed
     */
    public function render($name, $partial, array $options = array())
    {
        $partialHelper = $this->view->plugin('partial');

        if ($this->partialMapping === null) {
            $config = $this->getServiceManager()->get("Config");
            $this->partialMapping = $config['value_manager']['template_map'];
        }

        if (!array_key_exists($partial, $this->partialMapping)) {
            $partial = "*";
        }

        $partialName = $this->partialMapping[$partial];

        $model = array(
            'name'              => $name,
            'valueManager'      => $this->valueManager,
        );

        $model = array_merge($model, $options);

        return $partialHelper($partialName, $model);
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->getServiceLocator()->getServiceLocator();
    }
}
