<?php
/**
 * Template Name: With Header Image
 * The template file for pages without right sidebar.
 * @package Emmet
 * @since Emmet 1.0
 */
get_header();
?>
<div class="header-image-wrapper">
    <div class="header-image <?php if (get_header_image() != '') { ?>with-header-image <?php }?>" <?php if (get_header_image() != '') { ?>style="background-image: url(<?php header_image(); ?>);" <?php } ?>>
        <div class="container">
            <?php while (have_posts()) : the_post(); ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php
if(!(is_front_page())) :
?>
<div class="container breadcrumb-wrapper">
    <?php mp_emmet_the_breadcrumb(); ?>
</div>
<?php endif; ?>
<div class="container main-container">
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