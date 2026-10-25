<?php
class AccessLogs{
    function write_log($username) {
        $dir = LOGS . 'accesslogs/';
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $msg = date('Y-m-d H:i:s')
                . ',"' . $username . '"'
                . ',"' . env('REQUEST_URI') . '"'
                . ',"' . env('HTTP_REFERER') . '"'
                . ',"' . env('HTTP_USER_AGENT') . '"'
                . ',"' . env('REMOTE_ADDR') . '"'
                . "\n";
        $filename = LOGS . 'accesslogs/' . date('Ymd') . '.csv';
        $log = new File($filename);
        $log->append($msg);
        return true;
    }
}
?>