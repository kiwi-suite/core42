<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Form\Service;

use Zend\Form\FormInterface;
use Zend\ServiceManager\AbstractPluginManager;

class FormPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = FormInterface::class;

    /**
     * Should the services be shared by default?
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * @param string $name
     * @param array|null $options
     * @return mixed
     */
    public function get($name, array $options = null)
    {
        if (!$this->has($name)) {
            $this->setFactory($name, FormFactory::class);
        }

        return parent::get($name, $options);
    }
}
