<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation\Page;

abstract class PageFactory
{
    /**
     *
     */
    final protected function __construct()
    {

    }

    /**
     * @param array $spec
     * @param string $containerName
     * @return Page
     */
    public static function create(array $spec, $containerName)
    {
        $page = new Page();
        $page->setContainerName($containerName);

        if (isset($spec['pages'])) {
            foreach ($spec['pages'] as $childSpec) {
                $page->addPage(self::create($childSpec, $containerName));
            }
        }

        if (isset($spec['attributes'])) {
            $page->setAttributes($spec['attributes']);
        }

        if (isset($spec['options'])) {
            $page->setOptions($spec['options']);
        }

        return $page;
    }
}
