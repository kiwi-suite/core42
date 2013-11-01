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
            ),1
        ),
    );

    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */ 
    private $date;

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
