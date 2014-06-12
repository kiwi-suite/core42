<?php
namespace Core42\Db\SelectQuery\Service\Feature;

interface SelectQueryProviderInterface
{

    /**
     * @return array
     */
    public function getSqlQueryConfig();
}
