<?php
/*
 * lastnews section
 */
$mp_emmet_lastnews_animation_description = esc_attr(get_theme_mod('theme_lastnews_animation_description', 'fadeInRight'));
$mp_emmet_lastnews_animation = esc_attr(get_theme_mod('theme_lastnews_animation', 'fadeInLeft'));
?>
<section id="lastnews" class="lastnews-section grey-section default-section">
    <div class="container">
        <div class="section-content">
            <?php
            $mp_emmet_lastnews_title = esc_html(get_theme_mod('theme_lastnews_title'));
            $mp_emmet_lastnews_description = esc_html(get_theme_mod('theme_lastnews_description'));
            $mp_emmet_lastnews_button_url = esc_url(get_theme_mod('theme_lastnews_button_url'));
            $mp_emmet_lastnews_button_label = esc_html(get_theme_mod('theme_lastnews_button_label'));
            if (get_theme_mod('theme_lastnews_title', false) === false) :
                ?> 
                <h2 class="section-title"><?php _e('blog posts', 'emmet-lite'); ?></h2>
                <?php
            else:
                if (!empty($mp_emmet_lastnews_title)):
                    ?>
                    <h2 class="section-title"><?php echo $mp_emmet_lastnews_title; ?></h2>
                    <?php
                endif;
            endif;
            if (get_theme_mod('theme_lastnews_description', false) === false) :
                ?> 
                <?php if ($mp_emmet_lastnews_animation_description === 'none'): ?>
                    <div class="section-description">
                    <?php else: ?>
                        <div class="section-description animated anHidden" data-animation="<?php echo $mp_emmet_lastnews_animation_description; ?>">
                        <?php endif; ?> 
                        <?php _e('Keep in touch with the all the latest news and events', 'emmet-lite'); ?></div>
                    <?php
                else:
                    if (!empty($mp_emmet_lastnews_description)):
                        ?>
                        <?php if ($mp_emmet_lastnews_animation_description === 'none'): ?>
                            <div class="section-description">
                            <?php else: ?>
                                <div class="section-description animated anHidden" data-animation="<?php echo $mp_emmet_lastnews_animation_description; ?>">
                                <?php endif; ?> 
                                <?php echo $mp_emmet_lastnews_description; ?></div>
                            <?php
                        endif;
                    endif;
                    ?>
                    <div class="row">
                        <?php
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 4,
                            'post_status' => 'publish',
                            'orderby' => 'date',
                            'ignore_sticky_posts' => 1,
                        );
                        $prizes = new WP_Query($args);
                        if ($prizes->have_posts()) {
                            ?>
                            <div class="lastnews-list">
                                <?php
                                while ($prizes->have_posts()) {
                                    $prizes->the_post();
                                    ?>
                                    <?php if ($mp_emmet_lastnews_animation === 'none'): ?>
                                        <div id="post-<?php the_ID(); ?>" <?php post_class('post col-xs-12 col-sm-3 col-md-3 col-lg-3'); ?>>

                                        <?php else: ?> 
                                            <div id="post-<?php the_ID(); ?>" <?php post_class('post col-xs-12 col-sm-3 col-md-3 col-lg-3 animated anHidden'); ?>   data-animation="<?php echo $mp_emmet_lastnews_animation; ?>">
                                            <?php endif; ?> 
                                            <?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) : ?>
                                                <div class="entry-thumbnail">            
                                                    <a href = "<?php the_permalink(); ?>" ><?php the_post_thumbnail('mp-emmet-thumb-medium'); ?></a>
                                                </div>    
                                            <?php else:
                                                ?>
                                                <div class="entry-thumbnail empty-entry-thumbnail">
                                                    <a href = "<?php the_permalink(); ?>" rel="external" title="<?php the_title(); ?>"><span class="date-post ">
                                                            <?php echo get_post_time('j M'); ?>
                                                        </span></a>
                                                </div> 
                                            <?php endif; ?>
                                            <div class="entry-header">
                                                <h5 class="entry-title">
                                                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                                </h5>
                                            </div> 
                                            <div class="entry entry-content">
                                                <p>
                                                    <?php
                                                    mp_emmet_get_content_theme(95, false);
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                    <div class="clearfix"></div>
                                </div>
                                <?php
                            } else {
                                _e('No news!', 'emmet-lite');
                            }
                            ?>
                        </div>
                        <div class="section-buttons">
                            <?php
                            if (get_theme_mod('theme_lastnews_button_url', false) === false) :
                                ?>
                                <a href="#lastnews" title="<?php _e('view all posts', 'emmet-lite') ?>" class="button white-button"><?php _e('view all posts', 'emmet-lite') ?></a>
                                <?php
                            else:
                                if (!empty($mp_emmet_lastnews_button_label) && !empty($mp_emmet_lastnews_button_url)):
                                    ?>
                                    <a href="<?php echo $mp_emmet_lastnews_button_url; ?>" title="<?php echo $mp_emmet_lastnews_button_label; ?>" class="button white-button"><?php echo $mp_emmet_lastnews_button_label; ?></a>
                                    <?php
                                endif;
                            endif;
                            ?>
                        </div>

                    </div>
                </div>
                </section>
<?php

    