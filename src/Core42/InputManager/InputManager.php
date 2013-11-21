<?php
namespace Core42\InputManager;

use Zend\InputFilter\InputFilter;

class InputManager
{
    /**
     * @var InputFilter
     */
    private $inputFilter;

    /**
     * @var array
     */
    private $input = array();

    /**
     * @param InputFilter $inputFilter
     * @return \Core42\InputManager\InputManager
     */
    public function setInputFilter(InputFilter $inputFilter)
    {
        $this->inputFilter = $inputFilter;
        return $this;
    }

    /**
     * @return InputFilter
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }

    /**
     * @param array $input
     * @return \Core42\InputManager\InputManager
     */
    public function setInput(array $input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue($name)
    {
        if (!array_key_exists($name, $this->input)) {
            return null;
        }
        return $this->input[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasError($name)
    {
        if (!$this->inputFilter->has($name)){
            return false;
        }

        $errors = $this->inputFilter->get($name)->getMessages();
        return !empty($errors);
    }

    /**
     * @param $name
     * @return array
     */
    public function getMessages($name)
    {
        if (!$this->inputFilter->has($name)){
            return array();
        }

        return $this->inputFilter->get($name)->getMessages();
    }
}
