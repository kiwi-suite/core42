<?php
namespace Core42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class InputManager extends AbstractHelper
{
    /**
     * @var \Core42\InputManager\InputManager
     */
    private $inputManager;

    /**
     * @var string
     */
    private $partial;

    /**
     * @var string
     */
    private $name;

    /**
     * @return \Core42\View\Helper\InputManager
     */
    public function __invoke($name = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return "";
        }
    }

    /**
     * @param \Core42\InputManager\InputManager $inputManager
     * @return \Core42\View\Helper\InputManager
     */
    public function setInputManager(\Core42\InputManager\InputManager $inputManager)
    {
        $this->inputManager = $inputManager;
        return $this;
    }

    /**
     * @param $partial
     * @return \Core42\View\Helper\InputManager
     */
    public function setPartial($partial)
    {
        $this->partial = $partial;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function render($name = null)
    {
        if ($name === null) {
            $name = $this->name;
        }

        $partialHelper = $this->view->plugin('partial');

        $model = array(
            'inputManager' => $this->inputManager,
            'errorMessages' => $this->inputManager->getMessages($name),
        );

        return $partialHelper($this->partial, $model);
    }
}
