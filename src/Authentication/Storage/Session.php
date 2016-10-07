<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Authentication\Storage;

class Session extends \Zend\Authentication\Storage\Session
{
    /**
     * Defined by Zend\Authentication\Storage\StorageInterface
     *
     * @param  mixed $contents
     * @return void
     */
    public function write($contents)
    {
        parent::write($contents);

        $this->session->getManager()->regenerateId();
    }
}
