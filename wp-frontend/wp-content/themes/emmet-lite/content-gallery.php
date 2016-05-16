<?php
/**
 * The template for displaying posts in the Gallery post format
 *
 * Used for  index/archive/search.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
global $emmetPageTemplate;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-in-blog post'); ?>>
    <?php
    if (get_post_gallery()) :
        $gallery = get_post_gallery(get_the_ID(), false);
        ?>
        <div class="flexslider gallery">
            <ul class="slides">
                <?php
                foreach ($gallery['src'] AS $src) {
                    ?>
                    <li><a href = "<?php the_permalink(); ?>" ><img src="<?php echo esc_url($src); ?>" class="my-custom-class"/></a></li>
                <?php }
                ?>
            </ul>
        </div>
    <?php else: ?>
        <?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) : ?>
            <div class="entry-thumbnail">
                <?php if ($emmetPageTemplate == 'full-width'): ?>
                    <a href = "<?php the_permalink(); ?>" ><?php the_post_thumbnail('mp-emmet-thumb-large'); ?></a>
                <?php else: ?>               
                    <a href = "<?php the_permalink(); ?>" ><?php the_post_thumbnail(); ?></a>
                <?php endif; ?>
            </div>
            <?php
        endif;
    endif;
    ?>
    <div class="clearfix"></div>
    <?php mp_emmet_post_meta($post); ?>
</article><!-- #post -->