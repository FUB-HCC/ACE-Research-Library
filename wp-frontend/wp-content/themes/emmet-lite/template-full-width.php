<?php
/**
 * Template Name: Full Width
 * The template file for pages without right sidebar.
 * @package Emmet
 * @since Emmet 1.0
 */
get_header();
?>

<div class="main-container  <?php if( is_front_page()) :?>home-main-container<?php endif; ?>">
    <?php if (have_posts()) : ?>
        <?php /* The loop */ ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php if (has_post_thumbnail() && !post_password_required()) : ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail(); ?>
                        </div>
                    <?php endif; ?>
                    <?php the_content(); ?>
                </div><!-- .entry-content -->
            </article><!-- #post -->
        <?php endwhile; ?>
    <?php endif; ?>
</div>
<?php get_footer(); ?>