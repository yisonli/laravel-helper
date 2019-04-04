<?php

namespace LaravelHelper\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MonitorLog
{
    private static $instance = null;

    private function __construct()
    {
    }

    private $loggerInstance = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new MonitorLog();
        }
        return self::$instance;
    }

    public function warn($msg, $context = [], $file = null)
    {
        $file = $file ?: env('MONITOR_WARN_LOG');
        $logger = $this->getLogger($file);
        $logger->warn($this->handleData($msg), $this->handleData($context));
    }

    public function error($msg, $context = [], $file = null)
    {
        $file = $file ?: env('MONITOR_ERROR_LOG');
        $logger = $this->getLogger($file);
        $logger->error($this->handleData($msg), $this->handleData($context));
    }

    public function info($msg, $context = [], $file = null)
    {
        $file = $file ?: env('MONITOR_INFO_LOG');
        $logger = $this->getLogger($file);
        $logger->info($this->handleData($msg), $this->handleData($context));
    }

    /**
     * 日志脱敏
     * @param $data
     * @return array|mixed|string|string[]|null
     */
    public function handleData($data)
    {
        $res_data = $data;
        $filter = env('MONITOR_FILTER', false);
        if($filter) {
            if(is_array($data)) {
                $tmp = Sensitive::filter(json_encode($data));
                $res_data = json_decode($tmp, true);
                empty($res_data) && $res_data = [$tmp];     //避免脱敏后转不回array
            } else {
                $res_data = Sensitive::filter($data);
            }
        }
        return $res_data;
    }

    public function getLogger($file)
    {
        $key = md5($file);
        if (!isset($this->loggerInstance[$key])) {
            $log = new Logger('MONITOR_LOG');
            $handler = new StreamHandler($file);
            $pid = posix_getgid();
            $handler->setFormatter(
                new LineFormatter("%datetime%,001 [Thread-{$pid}] %level_name% [%channel%] [xxx:1] [trace=,span=,parent=,name=,app=,begintime=,endtime=] - %message% %context%\n")
            );
            $log->pushHandler($handler);
            $this->loggerInstance[$key] = $log;
        }
        return $this->loggerInstance[$key];
    }

}