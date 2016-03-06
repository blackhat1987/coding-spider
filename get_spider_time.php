<?php
/**
 * 获取爬虫采集时间
 * @author LincolnZhou<875199116@qq.com>
 */

require_once './core/bootstrap.php';
require_once './core/config.php';
require_once './core/db.php';

$first = Db::getInstance()->query('select add_time from user order by id ASC limit 1 ');
$last = Db::getInstance()->query('select add_time from user order by id DESC limit 1 ');

echo "开始时间：" . date('Y-m-d H:i:s', $first[0]['add_time']) . "\n";
echo "结束时间：" . date('Y-m-d H:i:s', $last[0]['add_time']) . "\n";