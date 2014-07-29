<?php
namespace Core42\Queue\Adapter;

use Core42\Queue\Job;

interface AdapterInterface
{
    /**
     * @param array $options
     */
    public function setOptions(array $options = array());

    /**
     * @param Job $job
     */
    public function push(Job $job);

    /**
     * @return Job|null
     */
    public function pop();

    /**
     * @return int
     */
    public function count();
}
