<?php
/**
 * The template for displaying Search Results pages
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
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('content', 'search'); ?>
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
                <article id="post-0" class="post no-results not-found">
                    <div class="entry-content">
                        <h3 class="entry-title"><?php _e('Nothing Found', 'emmet-lite'); ?></h3>
                        <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'emmet-lite'); ?></p>
                        <?php get_search_form(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-0 -->
            <?php endif; ?>

        </div>
        <div class=" col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

