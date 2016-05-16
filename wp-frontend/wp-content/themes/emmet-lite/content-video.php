<?php
/**
 * The template for displaying posts in the Video post format
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
    <?php echo mp_emmet_get_first_embed_media($post->ID); ?>
    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
    </header> 
    <section class="entry-content">
        <p>
            <?php
            mp_emmet_get_content_theme(107, false);
            ?>
        </p>
    </section>
    <?php mp_emmet_post_meta($post); ?>
</article><!-- #post -->