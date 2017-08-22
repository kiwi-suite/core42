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
