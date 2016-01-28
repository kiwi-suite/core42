<?php
class Migration20160128080040
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `cron`
ADD COLUMN `group` VARCHAR(45) NULL DEFAULT NULL AFTER `name`";

        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `cron`
DROP COLUMN `group`";

        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
