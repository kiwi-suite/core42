<?php
namespace Core42\Db\TableGateway\Service\Feature;

interface TableGatewayProviderInterface
{
    /**
     * @return array
     */
    public function getTableGatewayConfig();
}
