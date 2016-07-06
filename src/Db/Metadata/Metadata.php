<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Metadata;
use Zend\Db\Metadata\Source\Factory;
use Zend\Db\Adapter\Adapter;

class Metadata extends \Zend\Db\Metadata\Metadata
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * Metadata constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);

        $this->adapter = $adapter;
    }

    /**
     * @throws \Exception
     */
    public function refresh()
    {
        $this->source = Factory::createSourceFromAdapter($this->adapter);
    }
}
