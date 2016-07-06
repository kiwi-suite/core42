<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation\Provider;

use Core42\Navigation\Container;

interface ProviderInterface
{
    /**
     * @param string $containerName
     * @return Container
     */
    public function getContainer($containerName);

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options);
}
