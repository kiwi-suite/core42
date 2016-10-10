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

use Zend\View\Helper\AbstractHelper;

class AssetUrl extends AbstractHelper
{
    /**
     * @var string
     */
    protected $assetUrl;

    public function __construct($assetUrl)
    {
        $this->setAssetUrl($assetUrl);
    }

    /**
     * @param null|string $file
     * @return string
     * @throws \Exception
     */
    public function __invoke($file = null)
    {
        if (null === $this->assetUrl) {
            throw new \Exception('No base path provided');
        }

        if (null !== $file) {
            $file = '/' . ltrim($file, '/');
        }

        return $this->assetUrl . $file;
    }

    /**
     * @param  string $assetUrl
     * @return $this
     */
    public function setAssetUrl($assetUrl)
    {
        $this->assetUrl = rtrim($assetUrl, '/');

        return $this;
    }
}
