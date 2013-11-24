<?php
namespace Core42\ValueManager;

class ValueManager
{

    /**
     * @var array
     */
    private $values = array();

    /**
     * @var array
     */
    private $errors = array();

    /**
     * @param  array                            $values
     * @return \Core42\ValueManager\FormManager
     */
    public function setValues(array $values)
    {
        foreach ($values as $name => $value) {
            $this->setValue($name, $value);
        }

        return $this;
    }

    /**
     * @param  string                           $name
     * @param  string                           $value
     * @return \Core42\ValueManager\FormManager
     */
    public function setValue($name, $value)
    {
        $this->values[$name] = $value;

        return $this;
    }

    /**
     * @param  string|null $name
     * @return array|null
     */
    public function getValue($name = null)
    {
        if ($name === null) {
            return $this->values;
        }

        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        return null;
    }

    /**
     * @return \Core42\ValueManager\FormManager
     */
    public function clearValues()
    {
        $this->values = array();

        return $this;
    }

    /**
     * @param  array                            $errors
     * @return \Core42\ValueManager\FormManager
     */
    public function setErrors(array $errors)
    {
        foreach ($errors as $name => $error) {
            $this->setError($name, $error);
        }

        return $this;
    }

    /**
     * @param  string                           $name
     * @param  string                           $error
     * @return \Core42\ValueManager\FormManager
     */
    public function setError($name, $error)
    {
        if (empty($this->errors[$name])) {
            $this->errors[$name] = array();
        }
        if (is_array($error)) {
            foreach ($error as $errorMessage) {
                $this->errors[$name][] = $errorMessage;
            }
        } elseif (is_string($error)) {
            $this->errors[$name][] = $error;
        }

        return $this;
    }

    /**
     * @return \Core42\ValueManager\FormManager
     */
    public function clearErrors()
    {
        $this->errors = array();

        return $this;
    }

    /**
     * @param  string|null $name
     * @return array
     */
    public function getErrors($name = null)
    {
        if ($name === null) {
            return $this->errors;
        }

        if (empty($this->errors[$name])) {
            return array();
        }

        return $this->errors[$name];
    }

    /**
     * @param  string|null $name
     * @return bool
     */
    public function hasErrors($name = null)
    {
        return (count($this->getErrors($name)) > 0);
    }
}
