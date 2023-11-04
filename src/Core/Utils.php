<?php

namespace ClaraPress;

class Utils
{
    /**
     * @param $level
     * @param $message
     * @param array $context
     */
    public static function log($level, $message, array $context = []): void
    {
        error_log(PHP_EOL . date('F j, Y, g:i a e O') . ' | ' . mb_strtoupper($level) . ': ' . $message . ' ' .
            (!empty($context) ? print_r($context, true) : ''), 3, CLARAPRESS_TOC_PLUGIN_ERROR_LOG_FILE);
    }
}
