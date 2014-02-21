<?php
namespace Core42\Command\Service\Feature;

interface CommandProviderInterface
{

    /**
     * @return array
     */
    public function getCommandConfig();
}
