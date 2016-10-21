<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Model;
use Core42\Stdlib\DateTime;

/**
 * @method Migration setName() setName(string $name)
 * @method string getName() getName()
 * @method Migration setCreated() setCreated(DateTime $created)
 * @method DateTime getCreated() getCreated()
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
