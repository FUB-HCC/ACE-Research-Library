<?php
/*
 * Big title section
 */
$mp_emmet_allowed_html =array(
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
    'br' => array(),
    'b' => array(),
    'strong' => array(),
    'p' => array(),
    'i' => array(),
);
$mp_emmet_bigtitle_title = esc_html(get_theme_mod('theme_bigtitle_title'));
$mp_emmet_bigtitle_description = wp_kses(get_theme_mod('theme_bigtitle_description'),$mp_emmet_allowed_html);
$mp_emmet_bigtitle_brandbutton_label = esc_html(get_theme_mod('theme_bigtitle_brandbutton_label'));
$mp_emmet_bigtitle_brandbutton_url = esc_url(get_theme_mod('theme_bigtitle_brandbutton_url'));
$mp_emmet_bigtitle_whitebutton_label = esc_html(get_theme_mod('theme_bigtitle_whitebutton_label'));
$mp_emmet_bigtitle_whitebutton_url = esc_url(get_theme_mod('theme_bigtitle_whitebutton_url'));
$mp_emmet_bigtitle_radio = get_theme_mod('theme_bigtitle_radio', 'd');
$mp_emmet_mp_slider = get_theme_mod('theme_mp_slider');
?>
<section id="big-section" class="big-section transparent-section">
    <?php if ($mp_emmet_bigtitle_radio == 'd'): ?>
        <div class="container">
            <div class="section-content">
                <?php
                if (get_theme_mod('theme_bigtitle_title', false) === false) :
                    ?> 
                    <h1 class="section-title"><?php _e('introducing the emmet theme', 'emmet-lite'); ?></h1>
                    <?php
                else:
                    if (!empty($mp_emmet_bigtitle_title)):
                        ?>
                        <h1 class="section-title"><?php echo $mp_emmet_bigtitle_title; ?></h1>
                        <?php
                    endif;
                endif;
                if (get_theme_mod('theme_bigtitle_description', false) === false) :
                    ?> 
                    <div class="section-description"><?php _e('Clean and responsive WordPress theme with a professional design created for corporate and portfolio websites. Emmet comes packaged with page builder and fully integrated with WordPress Customizer. Theme works perfectly with major WordPress plugins like WooCommerce, bbPress, BuddyPress and many others.', 'emmet-lite'); ?></div>
                    <?php
                else:
                    if (!empty($mp_emmet_bigtitle_description)):
                        ?>
                        <div class="section-description"><?php echo $mp_emmet_bigtitle_description; ?></div>
                        <?php
                    endif;
                endif;
                ?>
                <div class="section-buttons">
                    <?php
                    if (get_theme_mod('theme_bigtitle_brandbutton_label', false) === false) :
                        ?>
                        <a href="#features" title="<?php _e('Features', 'emmet-lite') ?>" class="button"><?php _e('Features', 'emmet-lite') ?></a>
                        <?php
                    else:
                        if (!empty($mp_emmet_bigtitle_brandbutton_label) && !empty($mp_emmet_bigtitle_brandbutton_url)):
                            ?>
                            <a href="<?php echo $mp_emmet_bigtitle_brandbutton_url; ?>" title="<?php echo $mp_emmet_bigtitle_brandbutton_label; ?>" class="button"><?php echo $mp_emmet_bigtitle_brandbutton_label; ?></a>
                            <?php
                        endif;
                    endif;
                    if (get_theme_mod('theme_bigtitle_whitebutton_label', false) === false) :
                        ?>
                        <a href="#welcome" title="<?php _e('Read more', 'emmet-lite') ?>" class="button white-button"><?php _e('Read more', 'emmet-lite') ?></a>
                        <?php
                    else:
                        if (!empty($mp_emmet_bigtitle_whitebutton_label) && !empty($mp_emmet_bigtitle_whitebutton_url)):
                            ?>
                            <a href="<?php echo $mp_emmet_bigtitle_whitebutton_url; ?>" title="<?php echo $mp_emmet_bigtitle_whitebutton_label; ?>" class="button white-button"><?php echo $mp_emmet_bigtitle_whitebutton_label; ?></a>
                            <?php
                        endif;
                    endif;
                    ?>
                </div>

            </div>
        </div>
        <?php
    else:
		echo do_shortcode($mp_emmet_mp_slider);
    endif;
    ?>
</section>
<?php
