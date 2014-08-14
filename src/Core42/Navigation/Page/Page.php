<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation\Page;

use Core42\Navigation\Container;

class Page extends Container
{
    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var Page|null
     */
    protected $parent;

    /**
     * @param $pageOrSpec
     * @return $this
     */
    public function addChild($pageOrSpec)
    {
        if (is_array($pageOrSpec)) {
            $pageOrSpec = PageFactory::create($pageOrSpec, $this->getContainerName());
        } elseif (!$pageOrSpec instanceof Page) {
            throw new \InvalidArgumentException('Valid page types are an array or an Page instance');
        }

        $pageOrSpec->setParent($this);
        $this->children[] = $pageOrSpec;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return null !== $this->parent;
    }

    /**
     * @param $parent
     * @return Page
     */
    public function setParent(Page $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return null|Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * @param string $option
     * @param mixed|null $default
     * @return mixed
     */
    public function getOption($option, $default = null)
    {
        return isset($this->options[$option]) ? $this->options[$option] : $default;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @param string $attribute
     * @param mixed|null $default
     * @return mixed
     */
    public function getAttribute($attribute, $default = null)
    {
        return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : $default;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
