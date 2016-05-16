<?php
/**
 * The template for displaying posts in the Image post format
 *
 * Used for masonry blog
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-in-blog '); ?>>
    <div class="post-masonry">
        <?php
        $img = mp_emmet_get_post_image();
        if (!empty($img)):
            ?>
            <div class="entry-thumbnail">              
                <a href = "<?php the_permalink(); ?>"><img src="<?php echo esc_url($img) ?>" class="attachment-post-thumbnail wp-post-image" alt="<?php the_title(); ?>"></a>
            </div>
            <?php
        else:
            if (has_post_thumbnail() && !post_password_required() && !is_attachment()) :
                ?>
                <div class="entry-thumbnail">             
                        <a href = "<?php the_permalink(); ?>" ><?php the_post_thumbnail(); ?></a>
                </div>
                <?php
            endif;
        endif;
        ?>       
    </div>
</article><!-- #post -->
