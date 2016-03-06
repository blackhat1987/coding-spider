<?php
/**
 * Redis实例化类
 * @author LincolnZhou<875199116@qq.com>
 */
class PRedis
{
    /**
     * 获取Redis实例
     * @return Redis
     */
    static public function getInstance()
    {
        static $instances = array();
        $key = getmypid();
        if (empty($instances[$key]))
        {
            $instances[$key] = new Redis();

            $instances[$key]->connect('127.0.0.1', '6379');
        }
        return $instances[$key];
    }
}