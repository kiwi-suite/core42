<?php
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
        'nonce'
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
