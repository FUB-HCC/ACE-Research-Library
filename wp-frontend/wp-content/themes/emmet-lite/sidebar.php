<?php
/**
 * The sidebar containing the secondary widget area
 *
 * Displays on posts and pages.
 *
 * If no active widgets are in this sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>
<aside id="sidebar">
    <div class="widget-area">
        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <?php dynamic_sidebar('sidebar-1'); ?>
        <?php else: ?>
            <?php
            $args = array(
                'before_title' => '<h3 class="widget-title h2">',
                'after_title' => '</h3>',
            );
            $instance = array();
            the_widget('WP_Widget_Search', $instance, $args);           
            
            the_widget('WP_Widget_Recent_Posts', $instance, $args);            
            
            the_widget('WP_Widget_Tag_Cloud', $instance, $args);
                       
            the_widget('WP_Widget_Meta', $instance, $args);
            ?> 
        <?php endif; ?>
    </div><!-- .widget-area -->
</aside>
