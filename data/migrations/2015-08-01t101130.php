<?php
class Migration20150801101130
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        /* @var \Zend\Db\Adapter\Adapter $db */
        $db = $serviceManager->get('Db\Master');

        $db->query("ALTER TABLE `cron` ADD COLUMN `logfile` VARCHAR(300) NULL DEFAULT NULL AFTER `interval_year`;
        ", $db::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        /* @var \Zend\Db\Adapter\Adapter $db */
        $db = $serviceManager->get('Db\Master');

        $db->query('ALTER TABLE `cron` DROP COLUMN `logfile`;', $db::QUERY_MODE_EXECUTE);
    }
}
