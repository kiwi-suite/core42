<?php
namespace Core42\Mvc\Environment;

class Environment
{
    /**
     * @var array
     */
    protected $environments = [];

    /**
     * @param string $environment
     */
    public function set($environment)
    {
        $this->environments[$environment] = true;
    }

    /**
     * @param string $environment
     */
    public function remove($environment)
    {
        unset($this->environments[$environment]);
    }

    /**
     * @param $environment
     * @return bool
     */
    public function is($environment)
    {
        return array_key_exists($environment, $this->environments);
    }
}
