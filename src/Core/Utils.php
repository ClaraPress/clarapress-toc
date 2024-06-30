<?php

namespace ClaraPressToc;

class Utils
{
    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public static function log(string $level, string $message, array $context = []): void
    {
        error_log(PHP_EOL . gmdate('F j, Y, g:i a e O') . ' | ' . mb_strtoupper($level) . ': ' . $message . ' ' .
            (!empty($context) ? print_r($context, true) : ''), 3, CLARAPRESS_TOC_PLUGIN_ERROR_LOG_FILE);
    }
}
