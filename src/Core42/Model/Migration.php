<?php
namespace Core42\Model;


class Migration extends AbstractModel
{
    /**
     * @param string $name
     * @return Migration
     */
    public function setName($name)
    {
        return $this->set('name', $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * @param \DateTime $created
     * @return Migration
     */
    public function setCreated(\DateTime $created)
    {
        return $this->set('created', $created);
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->get('created');
    }
}
