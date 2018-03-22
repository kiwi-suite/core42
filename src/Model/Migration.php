<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
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
