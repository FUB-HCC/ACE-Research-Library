<?php
/**
 * The sidebar containing the footer widget area
 *
 * If no active widgets in this sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>
<div  class="footer-sidebar">
    <div class="container" >
        <div class="row">            
            <?php
            $args = array(
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>'
            );
            $instance = array();
            ?>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <?php if (is_active_sidebar('sidebar-2')) : ?>                
                    <?php dynamic_sidebar('sidebar-2'); ?>               
                <?php else: 
                    if (has_action('mp_emmet_footer_default_widget_about')){
                        do_action('mp_emmet_footer_default_widget_about');
                    }
                     endif; ?>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <?php if (is_active_sidebar('sidebar-3')) : ?>
                    <?php dynamic_sidebar('sidebar-3'); ?>                
                <?php else: 
                    if (has_action('mp_emmet_footer_default_recent_posts')){
                        do_action('mp_emmet_footer_default_recent_posts');
                    }
                     endif; ?>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <?php if (is_active_sidebar('sidebar-4')) : ?>
                    <?php dynamic_sidebar('sidebar-4'); ?>                
                <?php else: 
                    if (has_action('mp_emmet_footer_default_widget_authors')){
                        do_action('mp_emmet_footer_default_widget_authors');
                    }
                     endif; ?>
            </div>
        </div><!-- .widget-area -->
    </div>
</div>