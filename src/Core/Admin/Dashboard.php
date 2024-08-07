<?php

namespace ClaraPressToc\Admin;

use ClaraPressToc\Enum;

class Dashboard
{
    private $home_page;
    private $log_page;

    public function __construct()
    {
        $this->home_page = new HomePage();
        $this->log_page = new LogPage();

        //register hooks
        add_action('admin_init', [$this, 'admin_init_callback']);
        add_action('admin_menu', [$this, 'admin_menu_callback']);
        add_action('admin_enqueue_scripts', [$this, 'load_resources_callback']);
    }

    /**
     * Fires as an admin screen or script is being initialized.
     * Is triggered before any other hook when a user accesses the admin area
     *
     * Runs BOTH on user-facing admin screens and on admin-ajax.php & admin-post.php as well
     * Roughly analogous to the more general ‘init’ hook, which fires earlier.
     *
     * ref: https://developer.wordpress.org/reference/hooks/admin_init/
     */
    public function admin_init_callback(): void
    {
        // Register a new setting for our page.
        register_setting(Enum::ADMIN_PAGE_OPTION_GROUP, Enum::ADMIN_PAGE_OPTION_NAME);
    }

    /**
     * To add the plugin's submenus & menu options to the admin panel’s menu structure
     *
     * Fires before the administration menu loads in the admin.
     * Runs after the basic admin panel menu structure is in place.
     *
     * Note:
     * Must not be placed in an admin_init action function because the admin_init action is called after admin_menu.
     *
     * ref: https://developer.wordpress.org/reference/hooks/admin_menu/
     */
    public function admin_menu_callback(): void
    {
        $this->home_page->registerMenu();
        $this->log_page->registerMenu();
    }

    /**
     * To load assets files (e.g: css & js) for Admins creen
     */
    public function load_resources_callback(): void
    {
        $screen = get_current_screen();

        // load the asset only when the admin page is on the slug of our plugin
        if ('clarapress-toc' === $screen->base) {
            wp_enqueue_style('clarapress-toc-css', CLARAPRESS_TOC_PLUGIN_DIR_URL . 'assets/css/clarapress-toc.css', CLARAPRESS_TOC_PLUGIN_VERSION);
            wp_enqueue_script('clarapress-toc-js', CLARAPRESS_TOC_PLUGIN_DIR_URL . 'assets/js/clarapress-toc.js', ['jquery'], CLARAPRESS_TOC_PLUGIN_VERSION);
        }
    }
}
