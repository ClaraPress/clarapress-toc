<?php

namespace ClaraPress;

use ClaraPress\Admin\Dashboard;
use ClaraPress\Enum;
use ClaraPress\Admin\LogPage;

class PluginManager
{
    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     *
     * @static
     */
    public static function plugin_activation()
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
    public static function plugin_deactivation()
    {
        self::doCleanup();
    }

    /**
     * triggered when a user has deactivated the plugin
     */
    public static function plugin_uninstall()
    {
        //cleanup portion
        //clear any database fields initially created by this plugin
        self::doCleanup();
    }

    public static function doCleanup()
    {
        delete_option(Enum::ADMIN_PAGE_OPTION_NAME);
        LogPage::clearErrorLog(CLARAPRESS_TOC_PLUGIN_ERROR_LOG_FILE);
    }

    /**
     * To call any logic that needs to run on the frontend or in background
     */
    public static function run()
    {

    }

    /**
     * Admin facing logic
     */
    public static function admin_init()
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

    public static function start()
    {
        //todo: initialise environment vars
        self::initEnvironmentVars();

        //todo: register/override hooks..etc
    }

    public static function initEnvironmentVars()
    {
        //example
//        if (isset($_ENV[Enum::ENV_API_KEY])) {
//            define('API_KEY', trim($_ENV[Enum::ENV_API_KEY]));
//        }
    }

    public static function doAdminUI()
    {
        new Dashboard();
    }
}
