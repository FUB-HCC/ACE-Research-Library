<?php
/**
 * Two Columns (Blog)
 * The template file for pages without right sidebar.
 * @package Emmet
 * @since Emmet 1.0
 */
if (!(is_front_page())) {
    $GLOBALS['emmetPageTemplate'] = 'two-columns';
}
?>

<div class="container two-columns-blog main-container">
    <?php if (have_posts()) : ?>
        <?php
        /* The loop */
        $index = -1;
        ?>
        <div class="row">
            <?php while (have_posts()) : the_post(); ?>
                <?php $index++; ?>          
                <?php
                if ($index == 2) {
                    echo '</div><div class="row">';
                    $index = 0;
                }
                ?>

                <?php get_template_part('content', 'columns'); ?>
            <?php endwhile; ?>
        </div>
        <?php
        $args = array(
            'prev_next' => False
        );
        ?>
        <nav class="navigation navigation-prev-next">
            <?php posts_nav_link('  ', 'previous', 'next'); ?>
        </nav><!-- .navigation -->
    <?php endif; ?>
</div>
<?php 