<?php
/**
 * The default template for displaying content
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
    <?php mp_emmet_post_thumbnail($post, $emmetPageTemplate); ?>
    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
    </header> 
    <section class="entry entry-content">
        <p>
            <?php
            mp_emmet_get_content_theme(198, false);
            ?>
        </p>
    </section>
    <?php mp_emmet_post_meta($post); ?>
</article><!-- #post -->