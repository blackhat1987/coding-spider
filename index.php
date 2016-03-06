<?php
/**
 * 爬虫脚本
 * @author LincolnZhou<875199116@qq.com>
 */
require_once './core/bootstrap.php';
require_once './core/config.php';
require_once './core/db.php';
require_once './core/curl.php';
require_once './core/predis.php';
require_once './core/log.php';
require_once './function.php';

$redis = PRedis::getInstance();

if ($redis->lLen('request_queue') == 0) {
    $redis->lPush('request_queue', 'lincolnzhou');
}

$max_connect = 40; //最大进程数

while(1) {
    echo "开始采集用户数据\n";
    $redis->connect('127.0.0.1', '6379');
    $total = $redis->lLen('request_queue');

    if ($total == 0) {
        echo "数据采集完毕\n";
        break;
    }

    $current_count = $total <= $max_connect ? $total : $max_connect;

    for ($i = 1; $i <= $current_count; ++$i) {
        $pid = pcntl_fork();
        if ($pid == -1) {
            echo "无法创建子进程\n";
            exit(0);
        }

        if (!$pid) {
            $startTime = microtime();
            $tmp_redis = PRedis::getInstance();
            $username = $tmp_redis->lPop('request_queue');
            if (empty($tmp_redis->zScore('already_get_queue', $username))) {
                saveUserInfo($username); //保存用户信息
                $friends = getUserFriends($username); //获取用户朋友信息
                $followers = getUserFollowers($username); //获取用户粉丝信息

                //$tmp_redis->set($username, 'friends_count', count($friends));
                if (!empty($friends)) {
                    foreach ($friends as $user)
                    {
                        if (empty($tmp_redis->zScore('already_get_queue', $user))) {
                            $tmp_redis->lpush('request_queue', $user);
                        }
                    }
                }

                //$tmp_redis->set($username, 'followers_count', count($followers));
                if (!empty($followers)) {
                    foreach ($followers as $user)
                    {
                        if (empty($tmp_redis->zScore('already_get_queue', $user))) {
                            $tmp_redis->lpush('request_queue', $user);
                        }
                    }
                }

                $tmp_redis->zAdd('already_get_queue', 1, $username);
                $tmp_redis->close();
                $endTime = microtime();
                $startTime = explode(' ', $startTime);
                $endTime = explode(' ', $endTime);
                $total_time = $endTime[0] - $startTime[0] + $endTime[1] - $startTime[1];
                $timecost = sprintf("%.2f",$total_time);
                echo "采集{$username}花费了  " . $timecost . "s\n";
            } else {
                echo "{$username}已经采集过了\n";
            }
            exit($i);
        }
        usleep(1000);
    }

    while (pcntl_waitpid(0, $status) != -1)
    {
        $status = pcntl_wexitstatus($status);
        if (pcntl_wifexited($status))
        {
            echo "yes";
        }
        echo "--------$status finished--------\n";
    }
}