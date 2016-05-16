<?php
/**
 * Implement a custom header for Emmet
 *
 * @link https://codex.wordpress.org/Custom_Headers
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */

/**
 * Set up the WordPress core custom header arguments and settings.
 *
 * @uses add_theme_support() to register support for 3.4 and up.
 * @uses theme_header_style() to style front-end.
 *
 * @since Emmet 1.0
 */
function mp_emmet_custom_header_setup() {
    $args = array(
        // Text color and image (empty to use none).
        'default-text-color' => '4f4f4f',
        'default-image' => '%s/images/headers/bg.png',
        // Set height and width, with a maximum value for the width.
        'height' => 217,
        'width' => 2000,
        // Callbacks for styling the header and the admin preview.
        'wp-head-callback' => 'mp_emmet_header_style',
        'admin-head-callback' => 'mp_emmet_header_style',
        'admin-preview-callback' => 'mp_emmet_header_style',
    );

    add_theme_support('custom-header', $args);

    $defaults = array(
        'default-image' => get_template_directory_uri() . '/images/main-bg.jpg',
        'default-color' => '#ffffff',
        'default-repeat' => 'no-repeat',
        'default-position-x' => 'center',
        'default-attachment' => 'fixed',
    );
    add_theme_support('custom-background', $defaults);
}

add_action('after_setup_theme', 'mp_emmet_custom_header_setup', 11);

/**
 * Style the header text displayed on the blog.
 *
 * get_header_textcolor() options: Hide text (returns 'blank'), or any hex value.
 *
 * @since Emmet 1.0
 */
function mp_emmet_header_style() {
    $theme_header_text_color = esc_attr(get_header_textcolor());
    $theme_color_text = esc_attr(get_option('theme_color_text'));
    $theme_color_primary = esc_attr(get_option('theme_color_primary'));
    $theme_color_primary_light = esc_attr(get_option('theme_color_primary_light'));
    $theme_color_primary_dark = esc_attr(get_option('theme_color_primary_dark'));
    $theme_welcome_image = esc_attr(get_theme_mod('theme_welcome_image'));
    $theme_third_image = esc_attr(get_theme_mod('theme_third_image'));
    ?>
    <style type="text/css" id="theme-header-css">

        <?php
        if (get_option('theme_background_image_size') != false):
            $theme_background_image_size = esc_attr(get_option('theme_background_image_size'));
            ?>
            body.custom-background{
                -webkit-background-size:<?php echo $theme_background_image_size; ?>;
                -moz-background-size:<?php echo $theme_background_image_size; ?>;
                -o-background-size:<?php echo $theme_background_image_size; ?>;
                background-size:<?php echo $theme_background_image_size; ?>;
            }
            <?php
        endif;
// Has the text been hidden?
        if (!display_header_text()) :
            ?>
            .site-description{
                display:none;
            }
        <?php endif; ?>
        <?php
        if ($theme_header_text_color != '4f4f4f' && $theme_header_text_color != ''):
            ?>       
            .site-title{
                color:#<?php echo $theme_header_text_color; ?>;
            }                          
            <?php
        endif;
        ?>
        <?php if ($theme_color_text != MP_EMMET_TEXT_COLOR && $theme_color_text != '') : ?>
            body,
            .top-header a,
            .comment-respond,
            .tabs a {
                color: <?php echo $theme_color_text; ?>;
            }
            <?php if (is_plugin_active('motopress-content-editor/motopress-content-editor.php') || is_plugin_active('motopress-content-editor-lite/motopress-content-editor.php')) : ?>
                .emmet .motopress-tabs-obj.ui-tabs.motopress-tabs-no-vertical .ui-tabs-nav li.ui-state-active a {
                    color: <?php echo $theme_color_text; ?>!important;
                }
                .mp-text-color-black,
                .emmet h4.motopress-posts-grid-title a{
                    color: <?php echo $theme_color_text; ?>;
                }  
            <?php endif; ?>
            <?php if (is_plugin_active('woocommerce/woocommerce.php')) : ?>
                .woocommerce ul.products li.product h3 {
                    color: <?php echo $theme_color_text; ?>;
                }
            <?php endif; ?>
            <?php if (is_plugin_active('bbpress/bbpress.php')) : ?>
                #bbpress-forums .bbp-topic-freshness-author a{
                    color: <?php echo $theme_color_text; ?>;
                }
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($theme_color_primary != MP_EMMET_BRAND_COLOR && $theme_color_primary != '') : ?>
            a,
            .error404 .site-main .page-title,
            a:hover,
            a:focus ,
            .masonry-blog .comments-count:hover,
            .social-profile.type1 a:hover,
            .top-header .current_page_item a,
            .top-header a:hover,
            .author-description h4,
            .required,
            .comment-list h4.fn,
            .tabs li.active a,
            .tabs a:hover,
            .sf-menu > li.current_page_item > a,
            .sf-menu > li.current-menu-item > a, 
            .sf-menu > li:hover > a,
            .site-footer .widget table tbody a,
            .site-main .tabs li.active a,
            .site-main .tabs a:hover,
            .testimonial-athor-name,
            .team-name,
            .home-menu.sf-menu > li.current > a,
            .site-main .accent-section .button.white-button:hover ,
            .sf-menu > li.menu-item-object-custom.current-menu-item.current > a{ 
                color: <?php echo $theme_color_primary; ?>;
            }
            .site-footer .social-profile a:hover {
                color: #ffffff;
            }
            .features-icon,
            .site-main .button.white-button:hover, .site-main button.white-button:hover, .site-main input[type="button"].white-button:hover, .site-main input[type="submit"].white-button:hover, .site-main .added_to_cart.white-button:hover, .site-footer .button.white-button:hover, .site-footer button.white-button:hover, .site-footer input[type="button"].white-button:hover, .site-footer input[type="submit"].white-button:hover, .site-footer .added_to_cart.white-button:hover, .main-header .button.white-button:hover, .main-header button.white-button:hover, .main-header input[type="button"].white-button:hover, .main-header input[type="submit"].white-button:hover, .main-header .added_to_cart.white-button:hover ,
            .sf-menu ul a ,.accent-section,
            .site-main .button, .site-main button, .site-main input[type="button"], .site-main input[type="submit"], .site-main .added_to_cart, .site-footer .button, .site-footer button, .site-footer input[type="button"], .site-footer input[type="submit"], .site-footer .added_to_cart, .main-header .button, .main-header button, .main-header input[type="button"], .main-header input[type="submit"], .main-header .added_to_cart{
                background: <?php echo $theme_color_primary; ?>;
            }
            .sf-menu ul > li:first-child > a {
                border-top: 1px solid <?php echo $theme_color_primary; ?>;
            }
            .navigation-prev-next a:hover, .nav-previous a:hover, .nav-prev a:hover, .nav-next a:hover, .motopress-posts-grid-load-more a:hover,
            .site-main .button.white-button:hover, .site-main .button.white-button:focus, .site-main button.white-button:hover, .site-main button.white-button:focus, .site-main input[type="button"].white-button:hover, .site-main input[type="button"].white-button:focus, .site-main input[type="submit"].white-button:hover, .site-main input[type="submit"].white-button:focus, .site-main .added_to_cart.white-button:hover, .site-main .added_to_cart.white-button:focus, .site-footer .button.white-button:hover, .site-footer .button.white-button:focus, .site-footer button.white-button:hover, .site-footer button.white-button:focus, .site-footer input[type="button"].white-button:hover, .site-footer input[type="button"].white-button:focus, .site-footer input[type="submit"].white-button:hover, .site-footer input[type="submit"].white-button:focus, .site-footer .added_to_cart.white-button:hover, .site-footer .added_to_cart.white-button:focus, .main-header .button.white-button:hover, .main-header .button.white-button:focus, .main-header button.white-button:hover, .main-header button.white-button:focus, .main-header input[type="button"].white-button:hover, .main-header input[type="button"].white-button:focus, .main-header input[type="submit"].white-button:hover, .main-header input[type="submit"].white-button:focus, .main-header .added_to_cart.white-button:hover, .main-header .added_to_cart.white-button:focus ,
            .navigation a.page-numbers:hover, .navigation .page-numbers.current {
                background: <?php echo $theme_color_primary; ?>;
                border: 2px solid <?php echo $theme_color_primary; ?>;
            }
            .portfolio-empty-thumbnail,
            .toTop,
            table thead,
            .widget #today,
            .thumb-related.thumb-default,
            .entry-thumbnail.empty-entry-thumbnail{
                background:<?php echo $theme_color_primary; ?>;
            }
            .portfolio-list .portfolio-title {
                border-bottom: 3px solid <?php echo $theme_color_primary; ?>;
            }
            .site-footer {
                border-top: 3px solid <?php echo $theme_color_primary; ?>;
            }
            blockquote {
                border-color:<?php echo $theme_color_primary; ?>;
            }
            blockquote:before {
                color:<?php echo $theme_color_primary; ?>;
            }
            .header-image.with-header-image,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-range{
                background-color:<?php echo $theme_color_primary; ?>;
            }
            <?php if (is_plugin_active('motopress-content-editor/motopress-content-editor.php') || is_plugin_active('motopress-content-editor-lite/motopress-content-editor.php')) : ?>
                .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-outline-circle .motopress-ce-icon-bg .motopress-ce-icon-preview, .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-outline-square .motopress-ce-icon-bg .motopress-ce-icon-preview,    
                .mp-theme-icon-brand ,
                .motopress-ce-icon-obj.mp-theme-icon-bg-brand .motopress-ce-icon-preview,
                .motopress-list-obj .motopress-list-type-icon .fa{
                    color: <?php echo $theme_color_primary; ?>;
                }
                .emmet .motopress-cta-obj .motopress-button-wrap .mp-theme-button-white:focus,
                .emmet .motopress-cta-obj .motopress-button-wrap .mp-theme-button-white:hover,
                .emmet .motopress-tabs-obj.ui-tabs.motopress-tabs-basic .ui-tabs-nav li a{
                    color: <?php echo $theme_color_primary; ?>!important;
                }
                .wrapper-mce-lite .motopress-cta-obj .motopress-cta.motopress-cta-style-3d{
                    background-color: <?php echo $theme_color_primary; ?>;
                }
                .motopress-service-box-obj.motopress-service-box-brand .motopress-service-box-icon-holder-rounded, .motopress-service-box-obj.motopress-service-box-brand .motopress-service-box-icon-holder-square, .motopress-service-box-obj.motopress-service-box-brand .motopress-service-box-icon-holder-circle,
                .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-rounded .motopress-ce-icon-bg, .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-square .motopress-ce-icon-bg, .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-circle .motopress-ce-icon-bg,
                .motopress-service-box-brand .motopress-service-box-icon-holder,
                .motopress-countdown_timer.mp-theme-countdown-timer-brand  .countdown-section,
                .motopress-cta-style-brand ,
                .motopress-table-obj .motopress-table.mp-theme-table-brand thead{
                    background: <?php echo $theme_color_primary; ?>;
                }
                .motopress-accordion-obj.ui-accordion.mp-theme-accordion-brand .ui-accordion-header .ui-icon{ 
                    background-color: <?php echo $theme_color_primary; ?>!important;
                }
                .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-outline-rounded .motopress-ce-icon-bg,
                .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-outline-circle .motopress-ce-icon-bg, .motopress-ce-icon-obj.mp-theme-icon-bg-brand.motopress-ce-icon-shape-outline-square .motopress-ce-icon-bg{
                    border-color:<?php echo $theme_color_primary; ?>;
                }
                .emmet .motopress-posts-grid-obj .motopress-load-more:hover, .emmet .motopress-posts-grid-obj .motopress-load-more:focus,
                .emmet .motopress-modal-obj .mp-theme-button-brand,
                .emmet .entry-content .motopress-service-box-obj .motopress-service-box-button-section .mp-theme-button-brand,
                .emmet .entry-content .motopress-button-group-obj .mp-theme-button-brand,
                .emmet .entry-content .motopress-button-obj .mp-theme-button-brand ,
                .emmet .motopress-button-obj .mp-theme-button-white:hover, .emmet .motopress-button-obj .mp-theme-button-white:focus, .emmet .motopress-modal-obj .mp-theme-button-white:hover, .emmet .motopress-modal-obj .mp-theme-button-white:focus, .emmet .motopress-download-button-obj .mp-theme-button-white:hover, .emmet .motopress-download-button-obj .mp-theme-button-white:focus, .emmet .motopress-button-group-obj .mp-theme-button-white:hover, .emmet .motopress-button-group-obj .mp-theme-button-white:focus ,
                .emmet .motopress-download-button-obj .mp-theme-button-brand
                {
                    background: <?php echo $theme_color_primary; ?>;
                    border: 2px solid <?php echo $theme_color_primary; ?>;
                }                 
                .emmet .motopress-image-slider-obj .flex-control-paging li a.flex-active, 
                .emmet .motopress-image-slider-obj .flex-control-paging li a:hover ,
                .emmet .motopress-posts_slider-obj .motopress-flexslider .flex-control-nav li a.flex-active,
                .emmet .motopress-image-slider-obj .flex-control-paging li a.flex-active,
                .emmet .motopress-image-slider-obj .flex-control-paging li a:hover {
                    background: <?php echo $theme_color_primary; ?> !important;
                }
            <?php endif; ?>
            <?php if (is_plugin_active('woocommerce/woocommerce.php')) : ?>
                .woocommerce .woocommerce-message, .woocommerce .woocommerce-info {
                    border-top: 2px solid <?php echo $theme_color_primary; ?>;
                }
                .woocommerce ul.products li.product .price, 
                .price ins .amount,
                .woocommerce p.stars a.active:after,
                .woocommerce p.stars a:hover:after, 
                .woocommerce .star-rating span,
                .woocommerce-cart .page-title,
                .cart-collaterals .amount {
                    color: <?php echo $theme_color_primary; ?>;
                }
                .woocommerce-pagination a:focus, .woocommerce-pagination a:hover, .woocommerce-pagination span ,
                .woocommerce ul.products .button:hover,
                .woocommerce ul.products .added_to_cart:hover {
                    background: <?php echo $theme_color_primary; ?>;
                    border-color: <?php echo $theme_color_primary; ?>;
                }
                .woocommerce span.onsale{
                    background-color: <?php echo $theme_color_primary; ?>;
                }
            <?php endif; ?>
            <?php if (is_plugin_active('bbpress/bbpress.php')) : ?>               
                #bbpress-forums li.bbp-header,
                #bbpress-forums li.bbp-footer {                    
                    background: <?php echo $theme_color_primary; ?>;
                }
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($theme_color_primary_dark != MP_EMMET_MENU_HOVER_COLOR && $theme_color_primary_dark != '') : ?>
            .sf-menu ul a ,
            .sf-menu ul > li.current_page_item:first-child > a{
                border-top: 1px solid <?php echo $theme_color_primary_dark; ?>;
            }
            .sf-menu ul > li:first-child > a:hover {
                border-top: 1px solid <?php echo $theme_color_primary_dark; ?>;
            }
            .sf-menu ul a:hover,
            .sf-menu ul .current_page_item a{
                background: <?php echo $theme_color_primary_dark; ?>;
            }                
        <?php endif; ?>
        <?php if ($theme_color_primary_light != MP_EMMET_LINK_HOVER_COLOR && $theme_color_primary_light != '') : ?>
            .site-main .button:hover, .site-main .button:focus, .site-main button:hover, .site-main button:focus, .site-main input[type="button"]:hover, .site-main input[type="button"]:focus, .site-main input[type="submit"]:hover, .site-main input[type="submit"]:focus, .site-main .added_to_cart:hover, .site-main .added_to_cart:focus, .site-footer .button:hover, .site-footer .button:focus, .site-footer button:hover, .site-footer button:focus, .site-footer input[type="button"]:hover, .site-footer input[type="button"]:focus, .site-footer input[type="submit"]:hover, .site-footer input[type="submit"]:focus, .site-footer .added_to_cart:hover, .site-footer .added_to_cart:focus, .main-header .button:hover, .main-header .button:focus, .main-header button:hover, .main-header button:focus, .main-header input[type="button"]:hover, .main-header input[type="button"]:focus, .main-header input[type="submit"]:hover, .main-header input[type="submit"]:focus, .main-header .added_to_cart:hover, .main-header .added_to_cart:focus{
                background: <?php echo $theme_color_primary_light; ?>;
            }
            <?php if (is_plugin_active('motopress-content-editor/motopress-content-editor.php') || is_plugin_active('motopress-content-editor-lite/motopress-content-editor.php')) : ?>
                .motopress-accordion-obj.ui-accordion.mp-theme-accordion-brand .ui-accordion-header:hover .ui-icon {
                    background-color: <?php echo $theme_color_primary_light; ?> !important;
                } 
                .emmet .motopress-modal-obj .mp-theme-button-brand:focus,
                .emmet .motopress-modal-obj .mp-theme-button-brand:hover,
                .emmet .motopress-download-button-obj .mp-theme-button-brand:hover, .emmet .motopress-download-button-obj .mp-theme-button-brand:focus,
                .emmet .entry-content .motopress-service-box-obj .motopress-service-box-button-section .mp-theme-button-brand:hover,
                .emmet .entry-content .motopress-service-box-obj .motopress-service-box-button-section .mp-theme-button-brand:focus,
                .emmet .entry-content .motopress-button-group-obj .mp-theme-button-brand:hover, .emmet .motopress-button-group-obj .mp-theme-button-brand:focus,
                .emmet .entry-content .motopress-button-obj .mp-theme-button-brand:hover, .emmet .motopress-button-obj .mp-theme-button-brand:focus {
                    background: <?php echo $theme_color_primary_light; ?> ;
                    border: 2px solid <?php echo $theme_color_primary_light; ?> ;
                }
            <?php endif; ?>
        <?php endif; ?>
        <?php if (get_theme_mod('theme_welcome_image', false) === false) : ?>
            .welcome-right{
                background: url("<?php echo get_template_directory_uri() . '/images/welcome-image.png'; ?>") no-repeat scroll left center rgba(0,0,0,0);
            }
            <?php
        else:
            if ($theme_welcome_image != '') :
                ?>
                .welcome-right{
                    background: url("<?php echo $theme_welcome_image; ?>") no-repeat scroll 4px center ;
                }
                <?php
            endif;
        endif;
        ?>
        <?php if (get_theme_mod('theme_third_image', false) === false) : ?>
            .third-left{
                background: url("<?php echo get_template_directory_uri() . '/images/third-image.png'; ?>") no-repeat scroll right center rgba(0,0,0,0);
            }
            <?php
        else:
            if ($theme_third_image != '') :
                ?>
                .third-left{
                    background: url("<?php echo $theme_third_image; ?>") no-repeat scroll right center ;
                }
                <?php
            endif;
        endif;
        ?>
    </style>
    <?php
}
