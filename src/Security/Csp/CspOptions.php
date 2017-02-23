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

namespace Core42\Security\Csp;

use Zend\Stdlib\AbstractOptions;

class CspOptions extends AbstractOptions
{
    /**
     * @var bool
     */
    protected $enable           = false;

    /**
     * @var bool
     */
    protected $nonce            = false;

    /**
     * @var bool|array
     */
    protected $connectSrc       = false;

    /**
     * @var bool|array
     */
    protected $fontSrc          = false;

    /**
     * @var bool|array
     */
    protected $imgSrc           = false;

    /**
     * @var bool|array
     */
    protected $mediaSrc         = false;

    /**
     * @var bool|array
     */
    protected $objectSrc        = false;

    /**
     * @var bool|array
     */
    protected $scriptSrc        = false;

    /**
     * @var bool|array
     */
    protected $styleSrc         = false;

    /**
     * @var bool|array
     */
    protected $defaultSrc       = false;

    /**
     * @var bool|array
     */
    protected $formAction       = false;

    /**
     * @var bool|array
     */
    protected $formAncestors    = false;

    /**
     * @var bool|array
     */
    protected $pluginTypes      = false;

    /**
     * @var bool|array
     */
    protected $childSrc         = false;

    /**
     * @var bool|array
     */
    protected $frameSrc         = false;

    /**
     * @var bool|array
     */
    protected $frameAncestors   = false;

    /**
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     * @return CspOptions
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @param bool $nonce
     * @return CspOptions
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getConnectSrc()
    {
        return $this->connectSrc;
    }

    /**
     * @param array|bool $connectSrc
     * @return CspOptions
     */
    public function setConnectSrc($connectSrc)
    {
        $this->connectSrc = $connectSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getFontSrc()
    {
        return $this->fontSrc;
    }

    /**
     * @param array|bool $fontSrc
     * @return CspOptions
     */
    public function setFontSrc($fontSrc)
    {
        $this->fontSrc = $fontSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getImgSrc()
    {
        return $this->imgSrc;
    }

    /**
     * @param array|bool $imgSrc
     * @return CspOptions
     */
    public function setImgSrc($imgSrc)
    {
        $this->imgSrc = $imgSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getMediaSrc()
    {
        return $this->mediaSrc;
    }

    /**
     * @param array|bool $mediaSrc
     * @return CspOptions
     */
    public function setMediaSrc($mediaSrc)
    {
        $this->mediaSrc = $mediaSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getObjectSrc()
    {
        return $this->objectSrc;
    }

    /**
     * @param array|bool $objectSrc
     * @return CspOptions
     */
    public function setObjectSrc($objectSrc)
    {
        $this->objectSrc = $objectSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getScriptSrc()
    {
        return $this->scriptSrc;
    }

    /**
     * @param array|bool $scriptSrc
     * @return CspOptions
     */
    public function setScriptSrc($scriptSrc)
    {
        $this->scriptSrc = $scriptSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getStyleSrc()
    {
        return $this->styleSrc;
    }

    /**
     * @param array|bool $styleSrc
     * @return CspOptions
     */
    public function setStyleSrc($styleSrc)
    {
        $this->styleSrc = $styleSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getDefaultSrc()
    {
        return $this->defaultSrc;
    }

    /**
     * @param array|bool $defaultSrc
     * @return CspOptions
     */
    public function setDefaultSrc($defaultSrc)
    {
        $this->defaultSrc = $defaultSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getFormAction()
    {
        return $this->formAction;
    }

    /**
     * @param array|bool $formAction
     * @return CspOptions
     */
    public function setFormAction($formAction)
    {
        $this->formAction = $formAction;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getFormAncestors()
    {
        return $this->formAncestors;
    }

    /**
     * @param array|bool $formAncestors
     * @return CspOptions
     */
    public function setFormAncestors($formAncestors)
    {
        $this->formAncestors = $formAncestors;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getPluginTypes()
    {
        return $this->pluginTypes;
    }

    /**
     * @param array|bool $pluginTypes
     * @return CspOptions
     */
    public function setPluginTypes($pluginTypes)
    {
        $this->pluginTypes = $pluginTypes;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getChildSrc()
    {
        return $this->childSrc;
    }

    /**
     * @param array|bool $childSrc
     * @return CspOptions
     */
    public function setChildSrc($childSrc)
    {
        $this->childSrc = $childSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getFrameSrc()
    {
        return $this->frameSrc;
    }

    /**
     * @param array|bool $frameSrc
     * @return CspOptions
     */
    public function setFrameSrc($frameSrc)
    {
        $this->frameSrc = $frameSrc;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getFrameAncestors()
    {
        return $this->frameAncestors;
    }

    /**
     * @param array|bool $frameAncestors
     * @return CspOptions
     */
    public function setFrameAncestors($frameAncestors)
    {
        $this->frameAncestors = $frameAncestors;
        return $this;
    }
}
