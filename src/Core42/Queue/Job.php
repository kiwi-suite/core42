<?php
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
