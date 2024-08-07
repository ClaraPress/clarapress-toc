<?php

namespace ClaraPressToc\Admin;

use ClaraPressToc\Enum;

class LogPage
{
    public function __construct()
    {
    }

    public function registerMenu(): void
    {
        add_submenu_page(
            Enum::ADMIN_PAGE_PARENT_SLUG,
            'Log Page',
            'Log Page',
            Enum::ADMIN_PAGE_CAPABILITY,
            Enum::ADMIN_PAGE_LOG_SLUG,
            [self::class, 'render_page_elements']
        );
    }

    public static function render_page_elements(): void
    {
        global $title;
        $error_log_file = CLARAPRESS_TOC_PLUGIN_ERROR_LOG_FILE;
        $error_msg = $txtlog_value = '';

        if (!current_user_can('manage_options')) {
            return;
        }

        //Clear error log
        if (isset($_POST['clearlog_btn'])) {
            if (isset($_POST['clear_log_nonce']) && wp_verify_nonce($_POST['clear_log_nonce'], 'clear_log_action')) {
                $error_msg = self::clearErrorLog($error_log_file);
            } else {
                // Nonce verification failed
                wp_die(__('Nonce verification failed', 'clarapress-toc'));
            }
        } else {
            $txtlog_value = self::fetchLogData($error_log_file);
        }

        //params to inject to the template
        $args = [
            'title' => $title,
            'error_msg' => $error_msg,
        ];
        //Passing the txt value via query_var since WordPress is trimming out all newlines if we inject as param
        set_query_var('txtlog_value', $txtlog_value);
        load_template(CLARAPRESS_TOC_PLUGIN_TEMPLATES . 'admin/log-tpl.php', true, $args);
    }

    /**
     * Fetches all error lines from the log files
     * Method Will always return a message, an error message in case of any failure
     *
     * @param string $log_file_path
     *
     * @return string
     */
    public static function fetchLogData(string $log_file_path): string
    {
        global $wp_filesystem;
        $log_file = $log_file_path;
        $max_lines = 100;
        $log_data = '';

        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        if (!file_exists($log_file)) {
            add_settings_error(Enum::ADMIN_PAGE_LOG_SLUG, Enum::ADMIN_PAGE_LOG_SLUG . '_message', 'The log seems empty!', 'warn');

            return '';
        }

        if (!$wp_filesystem->is_writable($log_file)) {
            add_settings_error(Enum::ADMIN_PAGE_LOG_SLUG, Enum::ADMIN_PAGE_LOG_SLUG . '_message', 'the log is not writable. Please chmod it to 0777', 'error');

            return '';
        }

        $log_data_array = file($log_file, FILE_SKIP_EMPTY_LINES);

        if ($log_data_array === false) {
            add_settings_error(Enum::ADMIN_PAGE_LOG_SLUG, Enum::ADMIN_PAGE_LOG_SLUG . '_message', 'Unable to open the log for read operation!', 'error');

            return '';
        }

        $lines = count($log_data_array);
        if ($lines == 0) {
            add_settings_error(Enum::ADMIN_PAGE_LOG_SLUG, Enum::ADMIN_PAGE_LOG_SLUG . '_message', 'The log is empty.', 'info');

            return '';
        }

        //We only want to display the latest 10 entries
        if ($max_lines < $lines) {
            for ($i = 0; $i < ($lines - $max_lines); ++$i) {
                unset($log_data_array[$i]);
            }
        }

        //now fetch all lines
        foreach ($log_data_array as $line) {
            $log_data = $log_data . "\r\n" . sanitize_text_field($line);
        }

        return ($log_data);
    }

    /**
     * Util to help clear the log file
     *
     * @param string $log_file_path
     *
     * @return bool
     */
    public static function clearErrorLog(string $log_file_path): bool
    {
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        if (!file_exists($log_file_path)) {
            add_settings_error(Enum::ADMIN_PAGE_LOG_SLUG, Enum::ADMIN_PAGE_LOG_SLUG . '_message', 'The log seems empty!', 'warn');

            return false;
        }

        if (!$wp_filesystem->is_writable($log_file_path)) {
            add_settings_error(Enum::ADMIN_PAGE_LOG_SLUG, Enum::ADMIN_PAGE_LOG_SLUG . '_message', 'the log is not writable. Please chmod it to 0777', 'error');

            return false;
        }

        if (file_exists($log_file_path)) {
            wp_delete_file($log_file_path);

            add_settings_error(Enum::ADMIN_PAGE_LOG_SLUG, Enum::ADMIN_PAGE_LOG_SLUG . '_message', 'the log was cleared', 'success');

            return true;
        }
    }
}
