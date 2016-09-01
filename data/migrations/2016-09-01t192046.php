<?php
class Migration20160901192046
{

    public function up(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `cron` 
DROP COLUMN `logfile`,
CHANGE COLUMN `last_run` `lastRun` DATETIME NULL DEFAULT NULL ,
CHANGE COLUMN `next_run` `nextRun` DATETIME NULL DEFAULT NULL ,
CHANGE COLUMN `interval_minute` `intervalMinute` VARCHAR(30) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
CHANGE COLUMN `interval_hour` `intervalHour` VARCHAR(30) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
CHANGE COLUMN `interval_day` `intervalDay` VARCHAR(30) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
CHANGE COLUMN `interval_month` `intervalMonth` VARCHAR(30) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
CHANGE COLUMN `interval_day_of_week` `intervalDayOfWeek` VARCHAR(30) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
CHANGE COLUMN `interval_year` `intervalYear` VARCHAR(30) CHARACTER SET 'utf8' NULL DEFAULT NULL";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(\Zend\ServiceManager\ServiceManager $serviceManager)
    {

    }


}
