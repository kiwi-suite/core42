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
use Core42\Navigation\Page\PageFactory;

class ArrayProvider extends AbstractProvider
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param string $containerName
     * @return Container
     */
    public function getContainer($containerName)
    {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        if (!isset($this->options['config'])) {
            throw new \RuntimeException('Cannot build container: missing config in options');
        }

        $this->container = new Container();
        $this->container->setContainerName($containerName);
        foreach ($this->options['config'] as $page) {
            $this->container->addPage(PageFactory::create($page, $containerName));
        }
        return $this->container;
    }
}
