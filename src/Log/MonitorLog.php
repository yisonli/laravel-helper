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
        $logger->warn($msg, $context);
    }

    public function error($msg, $context = [], $file = null)
    {
        $file = $file ?: env('MONITOR_ERROR_LOG');
        $logger = $this->getLogger($file);
        $logger->error($msg, $context);
    }

    public function info($msg, $context = [], $file = null)
    {
        $file = $file ?: env('MONITOR_INFO_LOG');
        $logger = $this->getLogger($file);
        $logger->info($msg, $context);
    }

    public function getLogger($file)
    {
        $key = md5($file);
        if (!isset($this->loggerInstance[$key])) {
            $log = new Logger('MONITOR_LOG');
            $handler = new StreamHandler($file);
            $pid = posix_getgid();
            $handler->setFormatter(
                new LineFormatter("%datetime%,001 [Thread-{$pid}] %level_name% [xxx] [xxx:1] [trace=,span=,parent=,name=,app=,begintime=,endtime=] - %message%\n")
            );
            $log->pushHandler($handler);
            $this->loggerInstance[$key] = $log;
        }
        return $this->loggerInstance[$key];
    }

}