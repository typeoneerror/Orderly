<?php

$post_types = get_post_types();
unset(
    $post_types['revision'],
    $post_types['nav_menu_item'],
    $post_types['attachment']
);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['orderly-sortables']))
{
    $sortables = (array)$_POST['orderly-sortables'];
    $valid = array();
    foreach ($sortables as $sortable)
    {
        if (post_type_exists($sortable))
        {
            $valid[] = $sortable;
        }
    }
    if (!empty($valid))
    {
        $message = "Sortable post types saved.";
        $sortable_option = join(',', $valid);
        update_option(ORDERLY_OPTION_NAME, $sortable_option);
    }
}

$option = get_option(ORDERLY_OPTION_NAME);
$options = explode(',', $option);

?>

<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php _e('Orderly Configuration', ORDERLY_DOMAIN); ?></h2>

    <?php
    if (!empty($message)):
    ?>
    <div class="updated">
        <p>
            <strong><?php _e($message, ORDERLY_DOMAIN); ?></strong>
        </p>
    </div>
    <?php
    endif;
    ?>

    <form name="orderly-settings-form" method="post" action="">
        <p>
            <?php _e("Mark the post types you wish to be sortable.", ORDERLY_DOMAIN); ?>
        </p>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Settings", ORDERLY_DOMAIN); ?>"/>
        </p>
        <ul class="orderly-post-type">
        <?php
            $i = 1;
            foreach ($post_types as $post_type):
                $post_type_object = get_post_type_object($post_type);
                if ($post_type_object):
        ?>
            <li>
                <label for="orderly-sortables-<?php echo $i; ?>">
                    <input<?php echo (in_array($post_type, $options) ? ' checked="checked"' : ''); ?> type="checkbox" name="orderly-sortables[]" value="<?php echo $post_type; ?>" id="orderly-sortables-<?php echo $i; ?>"/>
                    <span><?php echo $post_type_object->labels->name; ?> (<em><?php echo $post_type; ?></em>)</span>
                </label>
            </li>
        <?php
                endif;
                $i++;
            endforeach;
        ?>
        </ul>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Settings", ORDERLY_DOMAIN); ?>"/>
        </p>
    </form>
</div>
