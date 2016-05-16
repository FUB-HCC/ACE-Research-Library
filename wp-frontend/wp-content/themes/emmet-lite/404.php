<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
get_header();
?>
<div class="container main-container">
    <article id="page-404" <?php post_class(); ?>>
        <div class="entry-content">
            <h1 class="page-title"><?php _e('404', 'emmet-lite'); ?></h1>
            <h2><?php _e("Oops! That page can&#39;t be found.", 'emmet-lite'); ?></h2>
            <p><?php _e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'emmet-lite'); ?></p>
            <?php get_search_form(); ?>
        </div><!-- .entry-content -->        
        <?php get_sidebar('404'); ?>
    </article>
    
</div>
<?php get_footer(); ?>
