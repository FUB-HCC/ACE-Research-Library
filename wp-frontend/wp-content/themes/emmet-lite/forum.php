<?php
/**
 * The template for displaying Forum pages
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
get_header();
?>
<div class="container">
    <div>
        <?php if (have_posts()) : ?>
            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
    <?php get_footer(); ?>