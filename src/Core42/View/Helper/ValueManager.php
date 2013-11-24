<?php
namespace Core42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ValueManager extends AbstractHelper
{
    /**
     * @var \Core42\ValueManager\ValueManager
     */
    private $valueManager;

    /**
     * @var string
     */
    private $errorPartial;

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
     * @param $partial
     * @return \Core42\View\Helper\InputManager
     */
    public function setErrorPartial($partial)
    {
        $this->errorPartial = $partial;

        return $this;
    }

    /**
     * @param  string $name
     * @return string
     */
    public function renderError($name)
    {
        $partialHelper = $this->view->plugin('partial');

        $model = array(
            'valueManager' => $this->valueManager,
            'errorMessages' => $this->valueManager->getErrors($name),
        );

        return $partialHelper($this->errorPartial, $model);
    }

    /**
     * @param  string     $name
     * @param  bool       $escape
     * @return array|null
     */
    public function getValue($name, $escape = true)
    {
        $value = $this->valueManager->getValue($name);

        if ($escape === true && !empty($value)) {
            $escapeHelper = $this->view->plugin('escapeHtml');
            $value = $escapeHelper($value);
        }

        return $value;
    }

    /**
     * @param  string|null $name
     * @return bool
     */
    public function hasError($name = null)
    {
        return $this->valueManager->hasErrors($name);
    }
}
