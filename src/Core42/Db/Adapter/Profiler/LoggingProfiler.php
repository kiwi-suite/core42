<?php
namespace Core42\Db\Adapter\Profiler;

use Zend\Db\Adapter\Profiler\Profiler;
use Zend\Log\Logger;

class LoggingProfiler extends Profiler
{
    /**
     * @var Logger
     */
    private $logger;

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function profilerStart($target)
    {
        parent::profilerStart($target);
    }

    public function profilerFinish()
    {
        parent::profilerFinish();
        $this->logger->info(var_export($this->getLastProfile(), true));
    }
}
