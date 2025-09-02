<?php
// logger.php
function log_event($message) {
    $file = __DIR__ . '/logs/log.txt';
    $time = date('Y-m-d H:i:s');
    $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $uri  = $_SERVER['REQUEST_URI'] ?? 'CLI';

    $log = "[$time] [IP:$ip] [URI:$uri] $message" . PHP_EOL;
    file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
}
