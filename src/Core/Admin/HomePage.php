<?php

namespace ClaraPressToc\Admin;

use ClaraPressToc\Enum;

class HomePage
{
    public function __construct()
    {
    }

    public function registerMenu(): void
    {
        add_menu_page(
            null,
            'ClaraPress ToC',
            Enum::ADMIN_PAGE_CAPABILITY,
            Enum::ADMIN_PAGE_PARENT_SLUG,
            null,
            'dashicons-editor-removeformatting',
            21
        );
        add_submenu_page(
            Enum::ADMIN_PAGE_PARENT_SLUG,
            'Home Page',
            'Home',
            Enum::ADMIN_PAGE_CAPABILITY,
            Enum::ADMIN_PAGE_PARENT_SLUG,
            [self::class, 'render_page_elements']
        );
    }

    public static function render_page_elements(): void
    {
        global $title;
        $error_msg = '';

        if (!current_user_can('manage_options')) {
            return;
        }

        //todo: handle any POST

        //todo: params to inject to the template
        $args = [
            'title' => $title,
            'error_msg' => $error_msg,
        ];
        load_template(CLARAPRESS_TOC_PLUGIN_TEMPLATES . 'admin/home-tpl.php', true, $args);
    }
}
