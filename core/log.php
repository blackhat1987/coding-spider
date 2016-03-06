<?php
/**
 * 日志操作类
 * @author LincolnZhou<875199116@qq.com>
 */
class Log
{
    const ERROR = 'ERROR'; //错误
    const DEBUG = 'DEBUG'; //调试信息
    const INFO = 'INFO'; //输出信息
    const ALL = 'ALL'; //全部

    /**
     * 是否启用
     * @var bool
     */
    private $enable = true;

    /**
     * 日志保存路径
     * @var string
     */
    private $logPath = './log/';

    public function __construct()
    {
    }

    /**
     * 写入日志
     * @param string $log 日志信息
     * @param string $level
     */
    public static function write($log, $level = self::ALL)
    {
        if (defined('LOGDIR')) {
            $destination = LOGDIR . date('Y-m-d') . '.log';
        } else {
            $destination = './log/' . date('Y-m-d') . '.log';
        }

        $log_dir = dirname($destination);
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }

        $now = date('Y-m-d H:i:s');
        error_log("[{$now}] {$log}\r\n", 3,$destination);
    }
}