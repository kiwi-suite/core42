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
     * @return FilterComposite|\Zend\Stdlib\Hydrator\Filter\FilterInterface
     */
    public function getFilter()
    {
        $excludeFilter = new FilterComposite();
        $excludeFilter->addFilter("getInputFilterSpecification", new MethodMatchFilter("getInputFilterSpecification"));
        $excludeFilter->addFilter("isValid", new MethodMatchFilter("isValid"));

        $composite = new FilterComposite();
        $composite->addFilter("is", new IsFilter())
                    ->addFilter("has", new HasFilter())
                    ->addFilter("get", new GetFilter())
                    ->addFilter("parameter", new OptionalParametersFilter(), FilterComposite::CONDITION_AND)
                    ->addFilter("exclude", $excludeFilter, FilterComposite::CONDITION_AND);

        return $composite;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $inputFilterSpecifications = $this->getInputFilterSpecification();
        if (empty($inputFilterSpecifications)) {
            return true;
        }

        $factory = new Factory();
        $inputFilter = $factory->createInputFilter($inputFilterSpecifications);
        $hydrator = new ModelHydrator();

        return $inputFilter->setData($hydrator->extract($this))
                            ->isValid();
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->inputFilterSpecifications;
    }
}
