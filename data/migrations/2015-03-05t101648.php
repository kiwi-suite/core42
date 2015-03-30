<?php
class Migration20150305101648
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        /* @var \Zend\Db\Adapter\Adapter $db */
        $db = $serviceManager->get('Db\Master');

        $db->query("CREATE TABLE `cron` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `status` enum('auto','manual','disabled') COLLATE utf8_unicode_ci NOT NULL,
          `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `command` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
          `parameters` text COLLATE utf8_unicode_ci,
          `lock` datetime DEFAULT NULL,
          `last_run` datetime DEFAULT NULL,
          `next_run` datetime DEFAULT NULL,
          `interval_minute` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
          `interval_hour` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
          `interval_day` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
          `interval_month` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
          `interval_day_of_week` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
          `interval_year` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
          `created` datetime NOT NULL,
          `updated` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `name_unique` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ", $db::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        /* @var \Zend\Db\Adapter\Adapter $db */
        $db = $serviceManager->get('Db\Master');

        $db->query('DROP TABLE cron;', $db::QUERY_MODE_EXECUTE);
    }
}
