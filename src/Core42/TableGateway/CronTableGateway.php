<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class CronTableGateway extends AbstractTableGateway
{
    /**
     * @var string
     */
    protected $table = 'cron';

    /**
     * @var string
     */
    protected $modelPrototype = 'Core42\Model\Cron';

    /**
     * @var array
     */
    protected $databaseTypeMap = [];
}
