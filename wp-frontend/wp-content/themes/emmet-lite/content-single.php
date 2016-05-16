<?php
/**
 * The default template for displaying content
 *
 * Used for single post.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
global $emmetPageTemplate;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
        <?php mp_emmet_post_thumbnail($post, $emmetPageTemplate); ?>
        <?php the_content(); ?>        
        <div class="clearfix"></div>
        <?php wp_link_pages(array('before' => '<nav class="navigation paging-navigation wp-paging-navigation">', 'after' => '</nav>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
    </div><!-- .entry-content -->
    <footer class="entry-footer">
        <?php if (get_theme_mod('theme_show_meta', '1') === '1' || get_theme_mod('theme_show_meta')): ?>
            <div class="meta">
                <span class="author"><?php _e('Posted by', 'emmet-lite'); ?> </span><?php the_author_posts_link(); ?> 
                <span class="seporator">/</span>
                <span class="date-post h6"><?php echo get_post_time('F j, Y'); ?></span>
                <?php if (comments_open()) : ?>
                    <span class="seporator">/</span>
                    <a class="blog-icon underline" href="#comments" ><span><?php comments_number('0', '1', '%'); ?> <?php _e('Comments', 'emmet-lite'); ?></span></a>
                <?php endif; ?>
                <?php edit_post_link(__('Edit', 'emmet-lite'), '<span class="seporator">/</span> ', ''); ?>
            </div>        
        <?php endif; ?>
        <?php if (get_theme_mod('theme_show_tags', '1') === '1' || get_theme_mod('theme_show_tags')): ?>
            <?php the_tags('<div class="tags-wrapper"><h5>' . __("Tagged with", 'emmet-lite') . '</h5><div class="tagcloud">', '<span>,</span> ', '</div></div>'); ?> 
        <?php endif; ?>
        <?php if (get_theme_mod('theme_show_categories', '1') === '1' || get_theme_mod('theme_show_categories')): ?>
            <?php $categories = get_the_category_list('<span>,</span> ', 'multiple', $post->ID); ?>
            <?php if (!empty($categories)) : ?>
                <div class="wrapper-post-categories">
                    <h5><?php _e('Posted in', 'emmet-lite') ?></h5>
                    <?php echo $categories; ?>
                    <div class="clearfix"></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (get_the_author_meta('description') && is_multi_author()) : ?>
            <?php get_template_part('author-bio'); ?>
        <?php endif; ?>
        <?php
        $orig_post = $post;
        global $post;
        $tags = wp_get_post_tags($post->ID);
        if ($tags) {
            ?>
            <div class="posts-related">
                <h5><?php _e('Related posts', 'emmet-lite'); ?></h5>
                <div class="row">
                    <?php
                    $tag_ids = array();
                    foreach ($tags as $individual_tag)
                        $tag_ids[] = $individual_tag->term_id;
                    $args = array(
                        'tag__in' => $tag_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => 3
                    );
                    $my_query = new wp_query($args);
                    while ($my_query->have_posts()) {
                        $my_query->the_post();
                        ?>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) : ?>
                                <div class="thumb-related">
                                    <a rel="external" alt="<?php the_title(); ?>" href="<?php the_permalink() ?>">
                                        <?php the_post_thumbnail('mp-emmet-thumb-medium'); ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="thumb-related thumb-default">
                                    <a class="date-post h6" rel="external" alt="<?php the_title(); ?>" href="<?php the_permalink() ?>">
                                        <?php echo get_post_time('F j, Y'); ?>
                                    </a>
                                </div>                                
                            <?php endif; ?>
                            <div class="meta-box-related">
                                <div class="related-title">
                                    <a href="<?php the_permalink() ?>" alt="<?php the_title(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
            <?php
        }
        $post = $orig_post;
        wp_reset_query();
        ?>

    </footer><!-- .entry-meta -->
</article><!-- #post -->