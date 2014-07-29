<?php
namespace Core42\Queue;

interface QueueAwareInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function queueSetup(array $params);

    /**
     * @return Job
     */
    public function queueExtract();
}
