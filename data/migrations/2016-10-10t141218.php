<?php
class Migration20161010141218
{

    public function up(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        /* @var \Zend\Db\Adapter\Adapter $db */
        $db = $serviceManager->get('Db\Master');

        $db->query("CREATE TABLE `core42_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('auto','manual','disabled') COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `parameters` text COLLATE utf8_unicode_ci,
  `lock` datetime DEFAULT NULL,
  `lastRun` datetime DEFAULT NULL,
  `nextRun` datetime DEFAULT NULL,
  `intervalMinute` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `intervalHour` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `intervalDay` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `intervalMonth` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `intervalDayOfWeek` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `intervalYear` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
", $db::QUERY_MODE_EXECUTE);
    }

    public function down(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        /* @var \Zend\Db\Adapter\Adapter $db */
        $db = $serviceManager->get('Db\Master');

        $db->query('DROP TABLE cron;', $db::QUERY_MODE_EXECUTE);
    }
}
