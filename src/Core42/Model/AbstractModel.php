<?php
namespace Core42\Model;

use Core42\Hydrator\ModelHydrator;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\FilterProviderInterface;
use Zend\Stdlib\Hydrator\Filter\GetFilter;
use Zend\Stdlib\Hydrator\Filter\HasFilter;
use Zend\Stdlib\Hydrator\Filter\IsFilter;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Zend\Stdlib\Hydrator\Filter\OptionalParametersFilter;

abstract class AbstractModel implements FilterProviderInterface,
                                            InputFilterProviderInterface
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
     * @return ModelHydrator
     */
    protected  function getHydrator()
    {
        if ($this->hydrator === null) {
            $this->hydrator = new ModelHydrator();
        }
        return $this->hydrator;
    }

    /**
     * @return \Zend\InputFilter\InputFilterInterface
     */
    protected function getInputFilter()
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
     * @return FilterComposite|\Zend\Stdlib\Hydrator\Filter\FilterInterface
     */
    public function getFilter()
    {
        $composite = new FilterComposite();
        $composite->addFilter("is", new IsFilter())
                    ->addFilter("has", new HasFilter())
                    ->addFilter("get", new GetFilter())
                    ->addFilter("parameter", new OptionalParametersFilter(), FilterComposite::CONDITION_AND)
                    ->addFilter("getInputFilterSpecification", new MethodMatchFilter("getInputFilterSpecification"), FilterComposite::CONDITION_AND)
                    ->addFilter("isValid", new MethodMatchFilter("isValid"), FilterComposite::CONDITION_AND)
                    ->addFilter("getHydrator", new MethodMatchFilter("getHydrator"), FilterComposite::CONDITION_AND)
                    ->addFilter("getInputFilter", new MethodMatchFilter("getHydrator"), FilterComposite::CONDITION_AND);

        return $composite;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $this->filter();
        return $this->getInputFilter()
                            ->isValid();
    }

    public function filter()
    {
        $this->getHydrator()->hydrate(
            $this->getInputFilter()->setData($this->getHydrator()->extract($this))->getValues(),
            $this
        );
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->inputFilterSpecifications;
    }
}
