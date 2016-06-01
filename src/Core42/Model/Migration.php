<?php
namespace Core42\Model;

/**
 * @method Migration setName() setName(string $name)
 * @method string getName() getName()
 * @method Migration setCreated() setCreated(\DateTime $created)
 * @method \DateTime getCreated() getCreated()
 */
class Migration extends AbstractModel
{

    /**
     * @var array
     */
    protected $properties = [
        'name',
        'created',
    ];
}
