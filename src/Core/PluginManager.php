<?php

namespace ClaraPressToc;

use ClaraPressToc\Admin\Dashboard;
use ClaraPressToc\Admin\LogPage;

class PluginManager
{
    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     *
     * @static
     */
    public static function plugin_activation(): void
    {
        //plant a seed to help us detect the state "on activation"
        add_option(Enum::PLUGIN_KEY, true);
    }

    /**
     * Should remove any scheduled events
     * NOTE: The database data cleaning is handled by uninstall.php
     *
     * @static
     */
    public static function plugin_deactivation(): void
    {
        self::doCleanup();
    }

    /**
     * triggered when a user has deactivated the plugin
     */
    public static function plugin_uninstall(): void
    {
        //cleanup portion
        //clear any database fields initially created by this plugin
        self::doCleanup();
    }

    public static function doCleanup(): void
    {
        delete_option(Enum::ADMIN_PAGE_OPTION_NAME);
        LogPage::clearErrorLog(CLARAPRESS_TOC_PLUGIN_ERROR_LOG_FILE);
    }

    /**
     * To call any logic that needs to run on the frontend or in background
     */
    public static function run(): void
    {
        // Register the shortcode
        add_action('init', function () {
            add_shortcode(\ClaraPressToc\TableOfContents::TOC_SHORTCODE, [\ClaraPressToc\TableOfContents::class, 'load_toc']);
        }, 5);

        // Now Add id anchor attributes to the <Hx> html tags
        add_filter('the_content', [\ClaraPressToc\TableOfContents::class, 'fix_content_by_adding_anchors'], 5);
    }

    /**
     * Admin facing logic
     */
    public static function admin_init(): void
    {
        //if on plugin activation
        if (get_option(Enum::PLUGIN_KEY)) {
            //do anything during activation

            //then delete the seed
            delete_option(Enum::PLUGIN_KEY);
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        self::start();
    }

    public static function start(): void
    {
        //todo: initialise environment vars
        self::initEnvironmentVars();

        //todo: register/override hooks..etc
    }

    public static function initEnvironmentVars(): void
    {
        //example
        //        if (isset($_ENV[Enum::ENV_API_KEY])) {
        //            define('API_KEY', trim($_ENV[Enum::ENV_API_KEY]));
        //        }
    }

    public static function doAdminUI(): void
    {
        new Dashboard();
    }
}
