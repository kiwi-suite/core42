<?php
namespace Core42Test\Model;

use Core42\Model\AbstractModel;

class TestModel extends AbstractModel
{

    protected $inputFilterSpecifications = array(
        'id' => array(
            'filters' => array(
                array(
                    'name' => "int"
                ),
            ),
        ),
        'date' => array(
            'validators' => array(
                array(
                    'name' => 'isInstanceOf',
                    'options' => array(
                        'className' => '\DateTime',
                    ),
                ),
            ),
        ),
    );

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->set('id', $id);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        return $this->set('date', $date);
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->get('date');
    }
}
