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

use Zend\View\Helper\AbstractHelper;

class AssetUrl extends AbstractHelper
{
    /**
     * @var string
     */
    protected $assetUrl;

    /**
     * @var array
     */
    protected $assetConfig = [];

    public function __construct($assetUrl, array $assetConfig)
    {
        $this->setAssetUrl($assetUrl);

        $this->assetConfig = $assetConfig;
    }

    /**
     * @param null|string $file
     * @param null $name
     * @throws \Exception
     * @return string
     */
    public function __invoke($file = "", $name = null)
    {
        if (null === $this->assetUrl) {
            throw new \Exception('No base path provided');
        }

        $directory = "";
        if ($name !== null && !empty($this->assetConfig[$name])) {
            $directory = '/' . \trim($this->assetConfig[$name], '/');
        }

        if (!empty($file)) {
            $file = '/' . \ltrim($file, '/');
        }

        return $this->assetUrl . $directory . $file;
    }

    /**
     * @param  string $assetUrl
     * @return $this
     */
    public function setAssetUrl($assetUrl)
    {
        $this->assetUrl = \rtrim($assetUrl, '/');

        return $this;
    }
}
