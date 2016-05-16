<?php
/**
 * The template for displaying posts in the Gallery post format
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
        if (get_post_gallery()) :

            $gallery = get_post_gallery(get_the_ID(), false);
            ?>
            <div class="flexslider gallery">
                <ul class="slides">
                    <?php
                    foreach ($gallery['src'] AS $src) {
                        ?>
                        <li><a href = "<?php the_permalink(); ?>" ><img src="<?php echo esc_url($src); ?>" class="my-custom-class" /></a></li>
                    <?php }
                    ?>
                </ul>
            </div>
        <?php else: ?>
            <?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) : ?>
                <div class="entry-thumbnail">      
                        <a href = "<?php the_permalink(); ?>" ><?php the_post_thumbnail(); ?></a>
                </div>
                <?php
            endif;
        endif;
        ?>
    </div>
</article><!-- #post -->
