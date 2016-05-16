<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Emmet
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
<div class="container main-container">
    <div class="row clearfix">
        <div class=" col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('content', 'single'); ?>
                <?php comments_template(); ?>
            <?php endwhile; ?>
        </div>
        <div class=" col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>