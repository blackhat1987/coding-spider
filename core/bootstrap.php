<?php
/**
 * 启动宏定义文件
 * @author 周仕林<875199116@qq.com>
 */
date_default_timezone_set('Asia/Shanghai'); //设置时区
ini_set('display_errors', 0);

//系统常量定义
defined('APP_PATH') or define('APP_PATH', dirname(dirname(__FILE__)) . '/'); //系统目录
defined('CORE_PATH') or define('CORE_PATH', __DIR__ . 'bootstrap.php/'); //Core目录