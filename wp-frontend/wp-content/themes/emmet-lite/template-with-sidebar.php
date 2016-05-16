<?php
/**
 * Template Name: With Sidebar
 * The template file for pages with right sidebar.
 * @package Emmet
 * @since Emmet 1.0
 */
get_header();
if (!(is_front_page())) :
    ?>
    <div class="container breadcrumb-wrapper">
        <?php mp_emmet_the_breadcrumb(); ?>
    </div>
<?php endif; ?>
<div class="container main-container">
    <div class="row clearfix">
        <div class=" col-xs-12 col-sm-8 col-md-8 col-lg-8">
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
        <div class=" col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>