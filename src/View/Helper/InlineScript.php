<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\View\Helper;

class InlineScript extends \Zend\View\Helper\InlineScript
{
    /**
     * @var array
     */
    protected $optionalAttributes = [
        'charset',
        'crossorigin',
        'defer',
        'async',
        'language',
        'src',
        'nonce',
    ];

    /**
     * @param string $type
     * @param array $attributes
     * @param null $content
     * @return \stdClass
     */
    public function createData($type, array $attributes, $content = null)
    {
        $attributes['nonce'] = $this->view->plugin('csp')->getNonce();
        return parent::createData($type, $attributes, $content);
    }
}
