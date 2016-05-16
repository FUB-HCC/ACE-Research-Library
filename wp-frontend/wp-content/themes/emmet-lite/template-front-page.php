<?php

/**
 * Template Name: Customizable Front Page
 * The template file for front page.
 * @package Emmet
 * @since Emmet 1.3.9
 */
get_header();

if (have_posts()) :
    /* The loop */
    while (have_posts()) : the_post();
        get_template_part('custom-page');
    endwhile;
endif;

get_footer();
