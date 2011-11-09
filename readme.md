# Orderly

If you've ever made a WordPress-powered site that deviates from the normal blog structure,
you know how fantastic registering your own post types can be. If you want to order that
custom data manually though it's quite tricky. Orderly is a very simple **WordPress** plug-in that
allows you to add a simple drag and drop sortable to a post type including posts, pages, and
custom post types registered with `register_post_type`. No more sorting by date and manually changing
the post dates.


## Install

Download and extract the "orderly" plug-in folder into your wp-content/plugins directory,
or optionally, add as a git submodule if you are using git:

From the root of git repository:

        git submodule add git://github.com/typeoneerror/Orderly.git wp-content/plugins/orderly
        git submodule init
        git submodule update

Next log into the WordPress admin and "Activate" the Orderly plugin on the plugins page.

Orderly uses the jQuery UI Sortable plug-in which is bundled with Wordpress so there should
be no other dependencies.


## Using it

Installing the plug-in will add a menu to Settings > Orderly in the wp-admin. You can
select which post types you want to be sortable there. It will display "Post" and "Page" by default
as well as any types you register with `register_post_type`.

If you'd prefer to use the code directly, to add manual drag and drop ordering to a post type menu, simple use the
`orderly_register_orderable_post_type` convenience method in your *functions.php* template file.

For example, to make the built-in Pages sortable:

    orderly_register_orderable_post_type('page');

Or, if you've used `register_post_type` to register a custom post type with WordPress, Orderly
support can also be added to these. Example:

    register_post_type('work',
        array(
            'labels' => array(
                'name' => __('Work', MY_LANG),
                'singular_name' => __('Work', MY_LANG),
                'search_items' => __('Search Work', MY_LANG),
                'add_new_item' => __('Add New Work', MY_LANG),
                'edit_item' => __('Edit Work', MY_LANG),
                'new_item' => __('New Work', MY_LANG),
                'view_item' => __('View Work', MY_LANG),
                'not_found' => __('No work found.', MY_LANG),
            ),
            'description' => 'Content added to the work section is displayed in the portfolio.',
            'public' => true,
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 5,
            'rewrite' => array(
                'slug' => 'work',
            ),
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array('client', 'category', 'post_tag', 'page-attributes'),
        )
    );

    orderly_register_orderable_post_type('work');

You can wrap the plug-in calls in a test to make sure the plug-in is active:

    if (function_exists('orderly_register_orderable_post_type'))
    {
        orderly_register_orderable_post_type('work');
    }

Once registered, a sub-menu will appear under the post type sidebar menu. In our example above,
there would be a sidebar called "Work" which would have a submenu called "Order Work". Clicking
this link will take you to the admin page. Drag and drop the list of posts there to change the
order and click "Save Order" to save the order. The plug-in simply updates the `menu_order` property
of WordPress' posts table, so sort by that on the front end:

    $loop = new WP_Query(array(
        'post_type' => 'work',
        'order'     => 'ASC',
        'orderby'   => 'menu_order',
    ));
