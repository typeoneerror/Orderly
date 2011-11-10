<?php

/**
 * This file displays the Orderly Admin Page, rendering
 * the list of sortable post types and allowing simple
 * ordering by menu_order parameter.
 */

$post_type = trim($_REQUEST['post_type']);
if (empty($post_type)) $post_type = 'post';
$post_type_object = get_post_type_object($post_type);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $message = "Custom order saved for <em>{$post_type_object->labels->name}</em>";
    $values = (array)$_POST['orderly_values'];
    if (!empty($values))
    {
        global $wpdb;

        for ($i = 0; $i < count($values); $i++)
        {
            $post_id = (int)$values[$i];
            $sql = $wpdb->prepare(
                "UPDATE `{$wpdb->posts}` SET `menu_order` = %d WHERE ID = %d",
                $i,
                $post_id
            );
            $wpdb->query($sql);
        }
    }
}

$loop = new WP_Query(array(
    'post_type' => $post_type,
    'order'     => 'ASC',
    'orderby'   => 'menu_order',
    'nopaging'  => true,
));

?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo esc_html(__("Ordering " . esc_html($post_type_object->labels->name), ORDERLY_DOMAIN)); ?></h2>

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

    <?php if ($loop->have_posts()): ?>
    <form name="orderly-order-form" method="post" action="">
        <p>
            <?php _e("Drag and drop items to set the custom order.", ORDERLY_DOMAIN); ?>
        </p>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Order", ORDERLY_DOMAIN); ?>"/>
        </p>
        <ul class="orderly-items orderly-sortable">
        <?php
        $i = 1;
        while ($loop->have_posts()) : $loop->the_post(); ?>
            <li id="orderly-item-<?= the_ID(); ?>" class="<?= ($i % 2 == 0 ? 'alternate ' : ''); ?>ui-state-default">
                <span class="orderly-index"><?= $i; ?>.</span>
                <?= the_title(); ?>
                <input type="hidden" value="<?= the_ID(); ?>" name="orderly_values[]" id="orderly_values_<?= $i; ?>"/>
            </li>
        <?php
            $i++;
        endwhile;
        ?>
        </ul>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Order", ORDERLY_DOMAIN); ?>"/>
        </p>
    </form>
    <?php else: ?>
    <p>
        <?php $label = strtolower($post_type_object->labels->name); ?>
        <?php _e("There doesn't seem to be any {$label} yet. Click below to add one.", ORDERLY_DOMAIN); ?>
    </p>
    <p>
        <a href="<?= admin_url("post-new.php?post_type={$post_type}"); ?>" class="button-primary"><?php _e("Add {$post_type_object->labels->singular_name}", ORDERLY_DOMAIN); ?></a>
    </p>
    <?php endif; ?>
</div>
