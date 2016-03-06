<?php
/**
 * 保存用户信息
 * @param $username
 * @author LincolnZhou<875199116@qq.com>
 */
function saveUserInfo($username) {
    $user = Db::getInstance()->query('select * from user where username=:username', array('username' => $username));

    if (empty($user)) {
        echo "开始获取 {$username} 的信息\n";
        $userInfo = json_decode(Curl::request('GET', 'https://coding.net/api/user/key/' . $username), true);

        if (empty($userInfo)) {
            echo "{$username}采集失败";
            Log::write("{$username}采集失败", Log::ERROR);
        } else {
            if (isset($userInfo['data']) && !empty($userInfo['data'])) {
                $insertData = array();
                $userTableField = array(
                    'id' => 'user_id',
                    'name' => 'username',
                    'birthday' => 'birthday',
                    'company' => 'company',
                    'created_at' => 'created_at',
                    'follows_count' => 'follows_count',
                    'fans_count' => 'fans_count',
                    'global_key' => 'global_key',
                    'job' => 'job',
                    'last_logined_at' => 'last_logined_at',
                    'last_activity_at' => 'last_activity_at',
                    'location' => 'location',
                    'name_pinyin' => 'name_pinyin',
                    'slogan' => 'slogan',
                    'sex' => 'sex',
                    'tags' => 'tags',
                    'tags_str' => 'tags_str',
                    'tweets_count' => 'tweets_count',
                    'updated_at' => 'updated_at',
                    'status' => 'status'
                );

                foreach ($userInfo['data'] as $key => $value) {
                    if (isset($userTableField[$key])) {
                        $insertData[$userTableField[$key]] = $value;
                    }
                }

                $insertData['add_time'] = time();

                $user = Db::getInstance()->query('select * from user where username=:username', array('username' => $username));

                if ($user == false) {
                    Db::getInstance()->insert('user', $insertData);
                }

                echo "{$username}采集成功";
            } else {
                echo "{$username}采集失败";
                Log::write("{$username}采集失败", Log::ERROR);
            }
        }
    }
}

/**
 * 获取用户朋友信息
 * @author LincolnZhou<875199116@qq.com>
 * @param $username
 * @return array|null
 */
function getUserFriends($username) {
    $return = array();
    echo "开始获取 {$username} 的关注（朋友）信息\n";
    $friends = json_decode(Curl::request('GET', 'https://coding.net/api/user/friends/'. $username .'?page=1&pageSize=99999'), true);

    if (empty($friends)) {
        echo "{$username} 朋友信息采集失败";
        Log::write("{$username} 朋友信息采集失败", Log::ERROR);
    } else {
        if (isset($friends['data']) && !empty($friends['data']) && isset($friends['data']['list'])) {
            if (!empty($friends['data']['list'])) {
                foreach ($friends['data']['list'] as $list) {
                    $return[] = $list['global_key'];
                }

                return $return;
            } else {
                echo "{$username} 朋友信息为空";
            }
        } else {
            echo "{$username} 朋友信息采集失败";
            Log::write("{$username} 朋友信息采集失败", Log::ERROR);
        }
    }

    return $return;
}

/**
 * 获取用户朋友信息
 * @author LincolnZhou<875199116@qq.com>
 * @param $username
 * @return array|null
 */
function getUserFollowers($username) {
    $return = array();
    echo "开始获取 {$username} 的粉丝信息\n";
    $friends = json_decode(Curl::request('GET', 'https://coding.net/api/user/followers/'. $username .'?page=1&pageSize=99999'), true);

    if (empty($friends)) {
        echo "{$username} 粉丝信息采集失败";
        Log::write("{$username} 粉丝信息采集失败", Log::ERROR);
    } else {
        if (isset($friends['data']) && !empty($friends['data']) && isset($friends['data']['list'])) {
            if (!empty($friends['data']['list'])) {
                foreach ($friends['data']['list'] as $list) {
                    $return[] = $list['global_key'];
                }

                return $return;
            } else {
                echo "{$username} 粉丝信息为空";
            }
        } else {
            echo "{$username} 粉丝信息采集失败";
            Log::write("{$username} 粉丝信息采集失败", Log::ERROR);
        }
    }

    return $return;
}