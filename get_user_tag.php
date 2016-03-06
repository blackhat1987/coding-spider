<?php
/**
 * 获取所有用户标签脚本
 * @author LincolnZhou<875199116@qq.com>
 */
require_once './core/bootstrap.php';
require_once './core/config.php';
require_once './core/db.php';
require_once './core/curl.php';
require_once './core/predis.php';
require_once './core/log.php';
require_once './function.php';

//获取用户标签
$data = Curl::request('get', 'https://coding.net/api/tagging/user_tag_list');
$data = json_decode($data, true);
$tags = $data['data'];
foreach ($tags as $tag) {
    DB::getInstance()->insert('user_tag', array('id' => $tag['id'], 'name' => $tag['name']));
}