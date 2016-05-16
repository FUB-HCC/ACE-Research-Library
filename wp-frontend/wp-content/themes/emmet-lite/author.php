<?php
/**
 * The template for displaying author archives
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
<div class="container main-container">
    <div class="row clearfix">
        <div class=" col-xs-12 col-sm-8 col-md-8 col-lg-8">            
            <?php if (have_posts()) : ?>             
                <?php /* The loop */ ?>
                <?php if (get_the_author_meta('description') && is_multi_author()) : ?>
                    <?php get_template_part('author-bio'); ?>
                <?php endif; ?>
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

            <?php else : ?>
                <?php get_template_part('content', 'none'); ?>
            <?php endif; ?>

        </div>
        <div class=" col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
