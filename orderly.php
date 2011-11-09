<?php

/*
Plugin Name: Orderly
Description: Orderly adds a sub-menu to a post type that allows you to order the post type manually via drag and drop.
Version: 0.1
Author: Benjamin Borowski
Author URI: http://typeoneerror.com
License: GPL2
*/

/*
Copyright 2010 Benjamin Borowski (ben.borowski@typeoneerror.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ORDERLY_DEFAULT_CAPABILITY')) define('ORDERLY_DEFAULT_CAPABILITY', 'publish_pages');
if (!defined('ORDERLY_DOMAIN')) define('ORDERLY_DOMAIN', 'com.typeoneerror.wordpress.orderly');
if (!defined('ORDERLY_LIBRARY')) define('ORDERLY_LIBRARY', dirname(__FILE__) . "/library");
if (!defined('ORDERLY_SCRIPT')) define('ORDERLY_SCRIPT', 'orderly_script');
if (!defined('ORDERLY_STYLE')) define('ORDERLY_STYLE', 'orderly_style');

$__orderly_menus = array();

/**
 * Runs after Orderly is installed.
 *
 * @return void
 */
function orderly_install()
{
}

// include specific functionality based on the context
if (is_admin())
{
    register_activation_hook(dirname(__FILE__) . "/orderly.php", "orderly_install");
    include_once ORDERLY_LIBRARY . "/orderly-admin.php";
}
else
{
    include_once ORDERLY_LIBRARY . "/orderly-client.php";
}

/**
 * Register a post type as an orderable post type.
 *
 * @param string $post_type  Name of the post type registered with register_post_type.
 * @param array $options     Additional options as follows:
 *
 *     string capability:  Role required to order posts (http://codex.wordpress.org/Roles_and_Capabilities)
 *
 * @return void
 */
function orderly_register_orderable_post_type($post_type, $options = array())
{
    global $__orderly_menus;

    $defaults = array(
        'capability' => ORDERLY_DEFAULT_CAPABILITY, // default capability is Editor
    );
    $options = array_merge($defaults, $options);

    $__orderly_menus[$post_type] = $options;
}

/**
 * Unregister a post type previously registered with orderly_register_orderable_post_type.
 *
 * @param string $post_type  Name of custom post type.
 * @return void
 */
function orderly_unregister_orderable_post_type($post_type)
{
    global $__orderly_menus;

    if (isset($__orderly_menus[$post_type]))
    {
        unset($__orderly_menus[$post_type]);
    }
}

/**
 * Unregister all orderable types.
 *
 * @return void
 */
function orderly_unregister_all()
{
    global $__orderly_menus;

    $__orderly_menus = array();
}
