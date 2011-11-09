<?php

/**
 * Orderly functionality specific to the /wp-admin.
 */

// add css and js requirements
function orderly_admin_init()
{
    $style_url = plugins_url('css/style.css', dirname(__FILE__));
    wp_register_style(ORDERLY_STYLE, $style_url);

    $script_url = plugins_url('js/plugin.js', dirname(__FILE__));
    wp_register_script(ORDERLY_SCRIPT, $script_url, array('jquery', 'jquery-ui-sortable'));
}
add_action('admin_init', 'orderly_admin_init');

// render the admin page for a sortable type
function orderly_admin_page()
{
    include_once ORDERLY_LIBRARY . '/orderly-admin-page.php';
}

// render the orderly options page
function orderly_options_page()
{
    include_once ORDERLY_LIBRARY . '/orderly-options-page.php';
}

// add new menus to the post types
function orderly_admin_menu()
{
    global $__orderly_menus;

    foreach ($__orderly_menus as $post_type => $menu)
    {
        if (post_type_exists($post_type))
        {
            $post_type_object = get_post_type_object($post_type);

            $url = 'edit.php';
            if ($post_type != 'post') $url .= "?post_type={$post_type_object->name}";
            $title = __("Order {$post_type_object->labels->name}", ORDERLY_DOMAIN);
            $required_capability = !empty($menu['capability']) ? $menu['capability'] : ORDERLY_DEFAULT_CAPABILITY;
            $menu_slug = "{$post_type}_orderly";

            $page = add_submenu_page($url, $title, $title, $required_capability, $menu_slug, 'orderly_admin_page');

            add_action('admin_print_styles-' . $page, 'orderly_admin_styles');
            add_action('admin_print_scripts-' . $page, 'orderly_admin_scripts');
        }
    }

    add_options_page(__('Orderly', ORDERLY_DOMAIN), __('Orderly', ORDERLY_DOMAIN), 'manage_options', 'orderly', 'orderly_options_page');
}
add_action('admin_menu', 'orderly_admin_menu');

// add required styles
function orderly_admin_styles()
{
    wp_enqueue_style(ORDERLY_STYLE);
}

// add required scripts
function orderly_admin_scripts()
{
    wp_enqueue_script(ORDERLY_SCRIPT);
}
