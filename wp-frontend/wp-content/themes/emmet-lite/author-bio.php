<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>
<div class="author-wrapper">
    <div class="author-box">
        <div class="author-avatar">
            <?php echo get_avatar(get_the_author_meta( 'ID' ), '170', '', get_the_author()); ?>
        </div>
        <div class="author-description">
            <?php if (is_archive()): ?>
                <div class="h6"><?php _e('ABOUT THE AUTHOR', 'emmet-lite'); ?></div>
                    <?php
                endif;
                ?>
                <h4><?php the_author(); ?></h4>
                <p><?php the_author_meta('description'); ?> </p>                
            </div>  
        <div class="clearfix"></div>
        </div>  
    </div>