<?php
/**
 * Full Width (Blog)
 * The template file for pages without right sidebar.
 * @package Emmet
 * @since Emmet 1.0
 */

if ( !(is_front_page()) ) {
    $GLOBALS['emmetPageTemplate'] = 'full-width';
}
?>

<div class="container full-width-blog main-container">
    <?php if (have_posts()) : ?>
        <?php /* The loop */ ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('content', get_post_format()); ?>
        <?php endwhile; ?>
        <?php
        $args = array(
            'prev_next' => true
        );
        ?>
        <nav class="navigation paging-navigation">
            <?php echo paginate_links($args); ?>
        </nav><!-- .navigation -->
    <?php endif; ?>
</div>
<?php 