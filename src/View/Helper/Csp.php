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

namespace Core42\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class Csp extends AbstractHelper
{
    /**
     * @var \Core42\Security\Csp\Csp
     */
    protected $csp;

    /**
     * Csp constructor.
     * @param \Core42\Security\Csp\Csp $csp
     */
    public function __construct(\Core42\Security\Csp\Csp $csp)
    {
        $this->csp = $csp;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        return $this->csp->getNonce();
    }
}
