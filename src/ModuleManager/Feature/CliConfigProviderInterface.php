<?php
namespace Core42\ModuleManager\Feature;

interface CliConfigProviderInterface
{
    /**
     * @return mixed
     */
    public function getCliConfig();
}
