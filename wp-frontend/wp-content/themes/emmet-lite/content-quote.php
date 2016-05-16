<?php
/**
 * The template for displaying posts in the Quote post format
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
    <section class="entry entry-content">
        <?php
        the_content();
        ?>         
        <div class="clearfix"></div>
    </section>
    <?php mp_emmet_post_meta($post); ?>
</article><!-- #post -->


