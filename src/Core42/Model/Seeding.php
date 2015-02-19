<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Model;

class Seeding extends AbstractModel
{
    protected $properties = array(
        'name',
        'created',
    );

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
