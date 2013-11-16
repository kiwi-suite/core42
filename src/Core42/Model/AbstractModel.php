<?php
namespace Core42\Model;

use Core42\Hydrator\ModelHydrator;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\InputFilterInterface;

abstract class AbstractModel implements InputFilterProviderInterface
{
    /**
     * @var array
     */
    protected $inputFilterSpecifications = array();

    /**
     * @var ModelHydrator
     */
    private $hydrator;

    /**
     * @var \Zend\InputFilter\InputFilterInterface
     */
    private $inputFilter;

    /**
     * @var null|array
     */
    private $memento = null;

    public function __construct()
    {
        $this->memento();
    }

    /**
     * @return ModelHydrator
     */
    public function getHydrator()
    {
        if ($this->hydrator === null) {
            $this->hydrator = new ModelHydrator();
        }

        return $this->hydrator;
    }

    /**
     * @param array $data
     */
    public function hydrate(array $data)
    {
        $this->getHydrator()->hydrate($data, $this);
    }

    /**
     * @return array
     */
    public function extract()
    {
        return $this->getHydrator()->extract($this);
    }

    /**
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!($this->inputFilter instanceof \Zend\InputFilter\InputFilterInterface)) {
            $inputFilterSpecifications = $this->getInputFilterSpecification();
            if (empty($inputFilterSpecifications)) {
                return null;
            }

            $factory = new Factory();
            $this->inputFilter = $factory->createInputFilter($inputFilterSpecifications);
        }

        return $this->inputFilter;
    }

    /**
     * @param  string|array|null $options
     * @return bool
     */
    public function isValid($options = null)
    {
        $this->filter();

        if (is_string($options)) {
            $this->getInputFilter()->setValidationGroup(array($options));
        } elseif (is_array($options)) {
            $this->getInputFilter()->setValidationGroup($options);
        }

        $return = $this->getInputFilter()
                            ->isValid();

        $this->getInputFilter()->setValidationGroup(InputFilterInterface::VALIDATE_ALL);

        return $return;
    }

    /**
     *
     */
    public function filter()
    {
        $values = $this->getInputFilter()->setData($this->diff())->getValues();
        $hydrateValues = array_intersect_key($values, $this->diff());
        $this->hydrate($hydrateValues);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->inputFilterSpecifications;
    }

    /**
     * @return \Core42\Model\AbstractModel
     */
    public function memento()
    {
        $this->memento = $this->extract();

        return $this;
    }

    /**
     * @return bool
     */
    public function isMemento()
    {
        return ($this->memento !== null);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function diff()
    {
        if (!$this->isMemento()) {
            throw new \Exception("memento never called");
        }

        return array_udiff_assoc($this->extract(), $this->memento, function ($value1, $value2) {
            return ($value1 === $value2) ? 0 : 1;
        });
    }
}
