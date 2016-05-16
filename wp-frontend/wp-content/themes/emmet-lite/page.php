<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
get_header();
if (!(is_front_page())) :
    ?>
    <div class="container breadcrumb-wrapper">
        <?php mp_emmet_the_breadcrumb(); ?>
    </div>
<?php endif; ?>
<div class="container main-container <?php if( is_front_page()) :?>home-main-container<?php endif; ?>">
    <?php if (have_posts()) : ?>
        <?php /* The loop */ ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if (has_post_thumbnail() && !post_password_required()) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail(); ?>
                    </div>
                <?php endif; ?>
                <div class="entry-content">
                    <?php the_content(); ?>                    
                </div><!-- .entry-content -->
            </article><!-- #post -->
        <?php endwhile; ?>
    <?php endif; ?>

</div>

<?php get_footer(); ?>