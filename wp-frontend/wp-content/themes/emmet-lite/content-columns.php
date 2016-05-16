<?php
/**
 * The default template for displaying columns content
 *
 * Used for two columns blog
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
global $emmetPageTemplate;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-in-blog col-lg-6 col-md-6 col-sm-6 col-xs-12'); ?>>
    <?php mp_emmet_post_thumbnail($post, $emmetPageTemplate); ?>
    <header class="entry-header">
        <h2 class="entry-title h5">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
        <?php if (get_theme_mod('theme_show_meta', '1') === '1' || get_theme_mod('theme_show_meta')): ?>
            <div class="meta">
                <span class="date-post h6"><?php echo get_post_time('F j, Y'); ?></span>
                <?php if (comments_open()) : ?>
                    <span class="seporator">/</span>
                    <a class="blog-icon underline" href="#comments" >
                        <span><?php comments_number('0', '1', '%'); ?> <?php _e('Comments', 'emmet-lite'); ?></span>
                    </a>
                <?php endif; ?>
                <?php edit_post_link(__('Edit', 'emmet-lite'), '<span class="seporator">/</span> ', ''); ?>
            </div> 
        <?php endif; ?>
    </header> 
    <section class="entry-content">
        <p>
            <?php
            the_excerpt();
            ?>   
        </p>
        <p><a class="more-link underline" href="<?php the_permalink(); ?>"><?php _e('Read More', 'emmet-lite'); ?></a></p>
    </section>
</article><!-- #post -->
