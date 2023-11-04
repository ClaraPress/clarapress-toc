<?php
/**
 * ClaraPress Table of Contents
 *
 * @author Wasseem Khayrattee
 * @copyright 2022 Wasseem Khayrattee
 * @license GPL-3.0-only
 *
 * @wordpress-plugin
 * Plugin Name: ClaraPress Table of Contents
 * Plugin URI: TODO
 * Description: TODO
 * Version: 0.1.0
 * Requires at least: 6.2.0
 * Author: Wasseem Khayrattee
 * Author URI: https://github.com/wkhayrattee
 * License: GPL-3.0-only
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: clarapress-toc
 * Domain Path: /languages
 *
 *
 * reference: https://developer.wordpress.org/plugins/plugin-basics/header-requirements/
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version GPL-3.0-only.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Make sure we don't expose any info if called directly
 */
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

/**
 * Some global constants for our use-case
 */
define('CLARAPRESS_TOC_PLUGIN_VERSION', '0.1.0');
define('CLARAPRESS_TOC_PLUGIN_MINIMUM_WP_VERSION', '6.2.0');
define('CLARAPRESS_TOC_PLUGIN_DIR_URL', plugin_dir_url(__FILE__)); //has trailing slash at end
define('CLARAPRESS_TOC_PLUGIN_DIR', plugin_dir_path(__FILE__)); //has trailing slash at end
define('CLARAPRESS_TOC_PLUGIN_BASENAME', plugin_basename(CLARAPRESS_TOC_PLUGIN_DIR));
define('CLARAPRESS_TOC_PLUGIN_TEMPLATES', CLARAPRESS_TOC_PLUGIN_DIR . 'templates' . DIRECTORY_SEPARATOR);
define('CLARAPRESS_TOC_PLUGIN_ERROR_LOG_FILE', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'clarapress-toc_message.log');

/**
 * load our main file now with composer autoloading
 */
require_once CLARAPRESS_TOC_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'includes/vendor/autoload.php';

/**
 * Register main Hooks
 */
register_activation_hook(__FILE__, ['ClaraPress\\PluginManager', 'plugin_activation']);
register_deactivation_hook(__FILE__, ['ClaraPress\\PluginManager', 'plugin_deactivation']);

/**
 * Load the Admin-facing logic
 */
if (is_admin()) {
    add_action('init', ['ClaraPress\\PluginManager', 'admin_init']);
    \ClaraPress\PluginManager::doAdminUI();
}

/**
 * Load general frontend logic & background processes
 */
\ClaraPress\PluginManager::run();
