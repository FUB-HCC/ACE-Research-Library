<?php
/**
 * The home template file
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 
 */
get_header();

$mp_emmet_blog_type = esc_html(get_theme_mod('theme_blog_style','default'));

get_template_part( 'blog', $mp_emmet_blog_type );
 
get_footer();