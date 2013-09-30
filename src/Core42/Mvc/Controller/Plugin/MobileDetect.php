<?php
namespace Core42\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class MobileDetect extends AbstractPlugin
{
    /**
     * @var \Mobile_Detect
     */
    private $mobileDetect;

    /**
     * @param \Mobile_Detect $mobileDetect
     */
    public function setMobileDetect(\Mobile_Detect $mobileDetect)
    {
        $this->mobileDetect = $mobileDetect;
    }

    /**
     * @return \Mobile_Detect
     */
    public function getMobileDetect()
    {
        return $this->mobileDetect;
    }

    public function __call($method, $attributes)
    {
        return call_user_func_array(array($this->getMobileDetect(), $method), $attributes);
    }
}
