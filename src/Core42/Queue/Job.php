<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Queue;

class Job
{
    /**
     * @var string
     */
    private $serviceName;

    /**
     * @var array
     */
    private $params = array();

    /**
     * @param $serviceName
     * @param array $parans
     * @return Job
     */
    public static function factory($serviceName, array $parans = array())
    {
        $job =  new Job();
        $job->setServiceName($serviceName);
        $job->setParams($parans);

        return $job;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }
}
