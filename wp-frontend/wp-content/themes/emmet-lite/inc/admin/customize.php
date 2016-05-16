<?php

require get_template_directory() . '/inc/admin/customise-classes.php';
/**
 * Handles the theme's theme customizer functionality.
 *
 * @package    emmet
 */
/* Theme Customizer setup. */
add_action('customize_register', 'mp_emmet_customize_register');

/**
 * Sets up the theme customizer sections, controls, and settings.
 *
 * @since  1.0.0
 * @access public
 * @param  object  $wp_customize
 * @return void
 */
function mp_emmet_customize_register($wp_customize) {

    /*
     * Enable live preview for WordPress theme features. 
     */
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->add_section(
            'theme_header_image_section', array(
        'title' => '',
        'priority' => 60,
        'capability' => 'edit_theme_options',
            )
    );
    /*
     * Add 'gemeral' section 
     */
    $wp_customize->add_section(
            'theme_general_section', array(
        'title' => esc_html__('General', 'emmet-lite'),
        'priority' => 20,
        'capability' => 'edit_theme_options'
            )
    );
    /*
     *  Add the 'Show sticky menu' setting. 
     */
    $wp_customize->add_setting('theme_show_sticky_menu', array(
        'default' => 0,
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     * Add the upload control for the 'Show sticky menu' setting. 
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_show_sticky_menu', array(
        'label' => esc_html__('Sticky menu on scroll', 'emmet-lite'),
        'section' => 'theme_general_section',
        'settings' => 'theme_show_sticky_menu',
        'type' => 'checkbox',
        'priority' => 1
            ))
    );
    $wp_customize->add_setting('theme_background_image_size', array(
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'default' => 'cover',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('theme_background_image_size', array(
        'label' => __('Background Size', 'emmet-lite'),
        'section' => 'background_image',
        'settings' => 'theme_background_image_size',
        'type' => 'radio',
        'priority' => 50,
        'choices' => array(
            'auto' => 'Auto',
            'contain' => 'Contain',
            'cover' => 'Cover',
        ),
    ));


    /*
     * Add the 'copyright ' setting.
     */
    
    /*
     * Add the 'header image ' setting.
     */
    $wp_customize->add_setting(
            'theme_header_image_bg', array(
        'default' => get_template_directory_uri() . '/images/headers/bg.png',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'theme_sanitize_custom_image'
            )
    );
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Header_Image_Control($wp_customize, 'theme_header_image_bg', array(
        'label' => '',
        'section' => 'theme_header_image_section',
            ))
    );
    $theme_color_scheme = mp_emmet_get_color_scheme();
    /*
     *  Add color scheme setting and control.
     */
    $wp_customize->add_setting('theme_color_scheme', array(
        'default' => 'default',
        'sanitize_callback' => 'mp_emmet_sanitize_color_scheme',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('theme_color_scheme', array(
        'label' => __('Base Color Scheme', 'emmet-lite'),
        'section' => 'colors',
        'type' => 'select',
        'choices' => mp_emmet_get_color_scheme_choices(),
        'priority' => 1,
    ));
    /*
     * Brand Color
     */

    $wp_customize->add_setting('theme_color_text', array(
        'default' => MP_EMMET_TEXT_COLOR,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'theme_color_text', array(
        'label' => __('Text Color', 'emmet-lite'),
        'section' => 'colors',
        'settings' => 'theme_color_text'
    )));
    $wp_customize->add_setting('theme_color_primary', array(
        'default' => $theme_color_scheme[0],
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'theme_color_primary', array(
        'label' => __('Accent Color', 'emmet-lite'),
        'section' => 'colors',
        'settings' => 'theme_color_primary'
    )));
    $wp_customize->add_setting('theme_color_primary_light', array(
        'default' => $theme_color_scheme[1],
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'theme_color_primary_light', array(
        'label' => __('Accent Hover Color', 'emmet-lite'),
        'section' => 'colors',
        'settings' => 'theme_color_primary_light'
    )));
    $wp_customize->add_setting('theme_color_primary_dark', array(
        'default' => $theme_color_scheme[2],
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'theme_color_primary_dark', array(
        'label' => __('Sub-Menu Hover Color', 'emmet-lite'),
        'section' => 'colors',
        'settings' => 'theme_color_primary_dark'
    )));
    /*
     * Add 'logo' section 
     */
    $theme_logo_section_title = esc_html__('Logo', 'emmet-lite');
    if (!function_exists('has_site_icon')) {
        $theme_logo_section_title = esc_html__('Logo & Favicon', 'emmet-lite');
    }
    $wp_customize->add_section(
            'theme_logo_section', array(
        'title' => $theme_logo_section_title,
        'priority' => 30,
        'capability' => 'edit_theme_options'
            )
    );

    /*
     * Add the 'logo' upload setting.
     */
    $wp_customize->add_setting(
            'theme_logo', array(
        'default' => $theme_color_scheme[3],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
            )
    );

    /*
     * Add the upload control for the 'theme_logo' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Image_Control(
            $wp_customize, 'theme_logo', array(
        'label' => esc_html__('Logo', 'emmet-lite'),
        'section' => 'theme_logo_section',
        'settings' => 'theme_logo',
            )
            )
    );

    /*
     * Add 'header_info' section 
     */
    $wp_customize->add_section(
            'theme_header_info', array(
        'title' => esc_html__('Contact Information', 'emmet-lite'),
        'priority' => 60,
        'capability' => 'edit_theme_options'
            )
    );
    /*
     * Add the 'phone info' setting. 
     */
    $wp_customize->add_setting('theme_phone_info', array(
        'default' => MP_EMMET_DEFAULT_PHONE,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the control for the 'phone info' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_phone_info', array(
        'label' => __('Contact Information 1', 'emmet-lite'),
        'section' => 'theme_header_info',
        'settings' => 'theme_phone_info',
    )));
    /*
     * Add the 'location info' setting.
     */
    $wp_customize->add_setting('theme_location_info', array(
        'default' => MP_EMMET_DEFAULT_ADDRESS,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the control for the 'location info' setting. 
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_location_info', array(
        'label' => __('Contact Information 2', 'emmet-lite'),
        'section' => 'theme_header_info',
        'settings' => 'theme_location_info',
    )));

    /*
     * Add 'header_socials' section 
     */
    $wp_customize->add_section(
            'theme_header_socials', array(
        'title' => esc_html__('Social Links', 'emmet-lite'),
        'priority' => 80,
        'capability' => 'edit_theme_options'
            )
    );
    /*
     *  Add the 'facebook link' setting. 
     */
    $wp_customize->add_setting(
            'theme_facebook_link', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
            )
    );

    /*
     * Add the upload control for the 'facebook link' setting. 
     */
    $wp_customize->add_control(
            'theme_facebook_link', array(
        'label' => esc_html__('Facebook link', 'emmet-lite'),
        'section' => 'theme_header_socials',
        'settings' => 'theme_facebook_link',
            )
    );
    /*
     * Add the 'twitter link' setting. 
     */
    $wp_customize->add_setting(
            'theme_twitter_link', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'esc_url_raw',
            )
    );

    /*
     *  Add the upload control for the 'twitter link' setting. 
     */
    $wp_customize->add_control(
            'theme_twitter_link', array(
        'label' => esc_html__('Twitter link', 'emmet-lite'),
        'section' => 'theme_header_socials',
        'settings' => 'theme_twitter_link',
            )
    );

    /*
     * Add the 'linkedin link' setting. 
     */
    $wp_customize->add_setting(
            'theme_linkedin_link', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'esc_url_raw',
            )
    );

    /*
     * Add the upload control for the 'linkedin link' setting. 
     */
    $wp_customize->add_control(
            'theme_linkedin_link', array(
        'label' => esc_html__('LinkedIn link', 'emmet-lite'),
        'section' => 'theme_header_socials',
        'settings' => 'theme_linkedin_link',
            )
    );
    /*
     * Add the 'google plus link' setting. 
     */
    $wp_customize->add_setting(
            'theme_google_plus_link', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'esc_url_raw',
            )
    );

    /*
     * Add the upload control for the 'google plus link' setting. 
     */
    $wp_customize->add_control(
            'theme_google_plus_link', array(
        'label' => esc_html__('Google+ link', 'emmet-lite'),
        'section' => 'theme_header_socials',
        'settings' => 'theme_google_plus_link',
            )
    );
    /*
     * Add 'header_socials' section 
     */
    $wp_customize->add_section(
            'theme_posts_settings', array(
        'title' => esc_html__('Posts Settings', 'emmet-lite'),
        'priority' => 100,
        'capability' => 'edit_theme_options'
            )
    );
    /*
     *  Add blog type.
     */
    $wp_customize->add_setting('theme_blog_style', array(
        'default' => 'default',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
    ));

    $wp_customize->add_control('theme_blog_style', array(
        'label' => __('Blog Layout', 'emmet-lite'),
        'section' => 'theme_posts_settings',
        'type' => 'select',
        'choices' => mp_emmet_blog_list(),
        'priority' => 10,
    ));
    /*
     *  Add the 'Display meta' setting. 
     */
    $wp_customize->add_setting('theme_show_meta', array(
        'default' => '1',
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     * Add the upload control for the 'Display meta' setting. 
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_show_meta', array(
        'label' => esc_html__('Display Meta', 'emmet-lite'),
        'section' => 'theme_posts_settings',
        'settings' => 'theme_show_meta',
        'type' => 'checkbox',
            ))
    );
    /*
     * Add the 'Display Categories' setting. 
     */
    $wp_customize->add_setting('theme_show_categories', array(
        'default' => '1',
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     * Add the upload control for the 'Display Categories'setting. 
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_show_categories', array(
        'label' => esc_html__('Display Categories', 'emmet-lite'),
        'section' => 'theme_posts_settings',
        'settings' => 'theme_show_categories',
        'type' => 'checkbox',
            ))
    );
    /*
     * Add the 'Display Tags' setting. 
     */
    $wp_customize->add_setting('theme_show_tags', array(
        'default' => '1',
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     *  Add the upload control for the 'Display Tags' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_show_tags', array(
        'label' => esc_html__('Display Tags', 'emmet-lite'),
        'section' => 'theme_posts_settings',
        'settings' => 'theme_show_tags',
        'type' => 'checkbox',
            ))
    );

    /*
     * Add the 'Big title section'. 
     */
    $wp_customize->add_section(
            'theme_bigtitle_section', array(
        'title' => __('Big Title Section', 'emmet-lite'),
        'priority' => 80,
        'capability' => 'edit_theme_options'
            )
    );

    /*
     * Add the 'Hide big title section?' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_show', array(
        'default' => 0,
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox'
    ));
    /*
     *  Add the upload control for the 'Hide big title section?' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_bigtitle_show', array(
        'label' => esc_html__('Hide this section', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_show',
        'type' => 'checkbox',
        'priority' => 1,
            ))
    );
    $wp_customize->add_setting('theme_bigtitle_radio', array(
        'sanitize_callback' => 'mp_emmet_sanitize_radio',
        'default' => 'd',
    ));
    $wp_customize->add_control('theme_bigtitle_radio', array(
        'label' => __('This section displays', 'emmet-lite'),
        'priority' => 1,
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_radio',
        'type' => 'radio',
        'choices' => array(
            'd' => __('Section content', 'emmet-lite'),
            's' => __('MotoPress Slider', 'emmet-lite'),
        )
    ));
    /*
     * Add the 'Big title' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_title', array(
        'default' => __('introducing the emmet theme', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Big title' setting.
     */
    $wp_customize->add_control('theme_bigtitle_title', array(
        'label' => __('Title', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_title',
        'priority' => 2,
        'active_callback' => 'mp_emmet_choice_callback',
    ));

    /*
     * Add the 'Big title' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_description', array(
        'default' => __('Clean and responsive WordPress theme with a professional design created for corporate and portfolio websites. Emmet comes packaged with page builder and fully integrated with WordPress Customizer. Theme works perfectly with major WordPress plugins like WooCommerce, bbPress, BuddyPress and many others.', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Big title' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_bigtitle_description', array(
        'label' => __('Description', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_description',
        'priority' => 3,
        'active_callback' => 'mp_emmet_choice_callback',
    )));

    /*
     * Add the 'Big title brand button label' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_brandbutton_label', array(
        'default' => __('Features', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Big title brand button label' setting.
     */
    $wp_customize->add_control('theme_bigtitle_brandbutton_label', array(
        'label' => __('First button label', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_brandbutton_label',
        'priority' => 4,
        'active_callback' => 'mp_emmet_choice_callback',
    ));
    /*
     * Add the 'Big title brand button url' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_brandbutton_url', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Big title brand button url' setting.
     */
    $wp_customize->add_control('theme_bigtitle_brandbutton_url', array(
        'label' => __('First button url', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_brandbutton_url',
        'priority' => 5,
        'active_callback' => 'mp_emmet_choice_callback',
    ));

    /*
     * Add the 'Big title brand button label' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_whitebutton_label', array(
        'default' => __('Read more', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Big title brand button label' setting.
     */
    $wp_customize->add_control('theme_bigtitle_whitebutton_label', array(
        'label' => __('Second button label', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_whitebutton_label',
        'priority' => 6,
        'active_callback' => 'mp_emmet_choice_callback',
    ));
    /*
     * Add the 'Big title brand button url' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_whitebutton_url', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Big title brand button url' setting.
     */
    $wp_customize->add_control('theme_bigtitle_whitebutton_url', array(
        'label' => __('Second button url', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_whitebutton_url',
        'priority' => 7,
        'active_callback' => 'mp_emmet_choice_callback',
    ));
    /*
     * Add the 'Big title' setting. 
     */
    $wp_customize->add_setting('theme_bigtitle_position', array(
        'default' => 10,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_position'
    ));
    /*
     * Add the upload control for the 'Big title' setting.
     */
    $wp_customize->add_control('theme_bigtitle_position', array(
        'label' => __('Section position', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_bigtitle_position',
        'priority' => 30
    ));
    /*
     * Add the 'Big title slider' setting. 
     */
    $wp_customize->add_setting('theme_mp_slider', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
    ));
    /*
     * Add the upload control for the 'Big title slider' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_mp_slider', array(
        'label' => __('Shortcode of slider', 'emmet-lite'),
        'section' => 'theme_bigtitle_section',
        'settings' => 'theme_mp_slider',
        'priority' => 3,
        'active_callback' => 'mp_emmet_choice_callback',
    )));
    /*
     * Add the 'Welcome section'. 
     */
    $wp_customize->add_section(
            'theme_welcome_section', array(
        'title' => __('First Feature Section', 'emmet-lite'),
        'priority' => 81,
        'capability' => 'edit_theme_options'
            )
    );

    /*
     * Add the 'Hide welcome section?' setting. 
     */
    $wp_customize->add_setting('theme_welcome_show', array(
        'default' => 0,
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     *  Add the upload control for the 'Hide welcome section?' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_welcome_show', array(
        'label' => esc_html__('Hide this section', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'settings' => 'theme_welcome_show',
        'type' => 'checkbox',
        'priority' => 1,
            ))
    );
    /*
     * Add the 'Welcome' setting. 
     */
    $wp_customize->add_setting('theme_welcome_title', array(
        'default' => __('WordPress Customizer', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Welcome title' setting.
     */
    $wp_customize->add_control('theme_welcome_title', array(
        'label' => __('Title', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'settings' => 'theme_welcome_title',
        'priority' => 2,
    ));

    /*
     * Add the 'Welcome description' setting. 
     */
    $wp_customize->add_setting('theme_welcome_description', array(
        'default' => __('Build blocks, change theme colors, edit titles, manage widgets and see the results of the changes in real time. Make some pretty unique site designs without touching any code.', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Welcome description' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_welcome_description', array(
        'label' => __('Description', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'settings' => 'theme_welcome_description',
        'priority' => 3
    )));

    /*
     * Add the 'welcome button label' setting. 
     */
    $wp_customize->add_setting('theme_welcome_button_label', array(
        'default' => __('read more', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));

    /*
     * Add the upload control for the 'Welcome button label' setting.
     */
    $wp_customize->add_control('theme_welcome_button_label', array(
        'label' => __('Button label', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'settings' => 'theme_welcome_button_label',
        'priority' => 4
    ));
    /*
     * Add the 'Welcome button url' setting. 
     */
    $wp_customize->add_setting('theme_welcome_button_url', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));

    /*
     * Add the upload control for the 'Welcome button url' setting.
     */
    $wp_customize->add_control('theme_welcome_button_url', array(
        'label' => __('Button url', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'settings' => 'theme_welcome_button_url',
        'priority' => 5
    ));
    $wp_customize->add_setting('theme_welcome_animation_left', array(
        'default' => 'fadeInLeft',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_welcome_animation_left', array(
        'label' => __('Content animation', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 6,
    ));
    /*
     * Add the 'welcome image' upload setting.
     */
    $wp_customize->add_setting(
            'theme_welcome_image', array(
        'default' => get_template_directory_uri() . '/images/welcome-image.png',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
            )
    );

    /*
     * Add the upload control for the 'welcome image' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Image_Control(
            $wp_customize, 'theme_welcome_image', array(
        'label' => esc_html__('Image', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'settings' => 'theme_welcome_image',
        'priority' => 7
            )
            )
    );
    $wp_customize->add_setting('theme_welcome_animation_right', array(
        'default' => 'fadeInRight',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_welcome_animation_right', array(
        'label' => __('Image animation', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 8,
    ));
    /*
     * Add the 'Welcome' setting. 
     */
    $wp_customize->add_setting('theme_welcome_position', array(
        'default' => 20,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_position'
    ));
    $wp_customize->add_control('theme_welcome_position', array(
        'label' => __('Section position', 'emmet-lite'),
        'section' => 'theme_welcome_section',
        'settings' => 'theme_welcome_position',
        'priority' => 30,
    ));
    /*
     * Add the 'third section'. 
     */
    $wp_customize->add_section(
            'theme_third_section', array(
        'title' => __('Second Feature Section', 'emmet-lite'),
        'priority' =>82,
        'capability' => 'edit_theme_options'
            )
    );

    /*
     * Add the 'Hide third section?' setting. 
     */
    $wp_customize->add_setting('theme_third_show', array(
        'default' => 0,
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     *  Add the upload control for the 'Hide third section?' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_third_show', array(
        'label' => esc_html__('Hide this section', 'emmet-lite'),
        'section' => 'theme_third_section',
        'settings' => 'theme_third_show',
        'type' => 'checkbox',
        'priority' => 1,
            ))
    );
    /*
     * Add the 'third' setting. 
     */
    $wp_customize->add_setting('theme_third_title', array(
        'default' => __('Responsive Design', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Welcome title' setting.
     */
    $wp_customize->add_control('theme_third_title', array(
        'label' => __('Title', 'emmet-lite'),
        'section' => 'theme_third_section',
        'settings' => 'theme_third_title',
        'priority' => 2,
    ));

    /*
     * Add the 'Welcome description' setting. 
     */
    $wp_customize->add_setting('theme_third_description', array(
        'default' => __('Your content will automatically adapt to any screen size and look perfectly on all devices. Extend the boundaries of your web presence, create a website with full mobile support.', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'third description' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_third_description', array(
        'label' => __('Description', 'emmet-lite'),
        'section' => 'theme_third_section',
        'settings' => 'theme_third_description',
        'priority' => 3
    )));

    /*
     * Add the 'third button label' setting. 
     */
    $wp_customize->add_setting('theme_third_button_label', array(
        'default' => __('read more', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));

    /*
     * Add the upload control for the 'third button label' setting.
     */
    $wp_customize->add_control('theme_third_button_label', array(
        'label' => __('Button label', 'emmet-lite'),
        'section' => 'theme_third_section',
        'settings' => 'theme_third_button_label',
        'priority' => 4
    ));
    /*
     * Add the 'third button url' setting. 
     */
    $wp_customize->add_setting('theme_third_button_url', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'third button url' setting.
     */
    $wp_customize->add_control('theme_third_button_url', array(
        'label' => __('Button url', 'emmet-lite'),
        'section' => 'theme_third_section',
        'settings' => 'theme_third_button_url',
        'priority' => 5
    ));
    $wp_customize->add_setting('theme_third_animation_right', array(
        'default' => 'fadeInRight',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_third_animation_right', array(
        'label' => __('Content animation', 'emmet-lite'),
        'section' => 'theme_third_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 6,
    ));
    /*
     * Add the 'third image ' upload setting.
     */
    $wp_customize->add_setting(
            'theme_third_image', array(
        'default' => get_template_directory_uri() . '/images/third-image.png',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
            )
    );

    /*
     * Add the upload control for the 'third image' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Image_Control(
            $wp_customize, 'theme_third_image', array(
        'label' => esc_html__('Image', 'emmet-lite'),
        'section' => 'theme_third_section',
        'settings' => 'theme_third_image',
        'priority' => 7
            )
            )
    );
    $wp_customize->add_setting('theme_third_animation_left', array(
        'default' => 'fadeInLeft',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_third_animation_left', array(
        'label' => __('Image animation', 'emmet-lite'),
        'section' => 'theme_third_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 8,
    ));
    /*
     * Add section position
     */
    $wp_customize->add_setting('theme_third_position', array(
        'default' => 30,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_position'
    ));
    $wp_customize->add_control('theme_third_position', array(
        'label' => __('Section position', 'emmet-lite'),
        'section' => 'theme_third_section',
        'settings' => 'theme_third_position',
        'priority' => 30,
    ));
    /*
     * Add the 'Accent section'. 
     */
    $wp_customize->add_section(
            'theme_accent_section', array(
        'title' => __('Accent Section', 'emmet-lite'),
        'priority' => 87,
        'capability' => 'edit_theme_options'
            )
    );

    /*
     * Add the 'Hide accent section?' setting. 
     */
    $wp_customize->add_setting('theme_accent_show', array(
        'default' => 0,
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     *  Add the upload control for the 'Hide accent section?' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_accent_show', array(
        'label' => esc_html__('Hide this section', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'settings' => 'theme_accent_show',
        'type' => 'checkbox',
        'priority' => 1,
            ))
    );
    /*
     * Add the 'Accent' setting. 
     */
    $wp_customize->add_setting('theme_accent_title', array(
        'default' => __('Install Emmet theme now!', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Accent title' setting.
     */
    $wp_customize->add_control('theme_accent_title', array(
        'label' => __('Title', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'settings' => 'theme_accent_title',
        'priority' => 2,
    ));

    /*
     * Add the 'Accent description' setting. 
     */
    $wp_customize->add_setting('theme_accent_description', array(
        'default' => __('Ut varius tortor enim. Aliquam nec posuere tellus. Nunc mattis augue quam, vitae egestas massa elementum in. Nunc molestie velit at tempor ornare. Maecenas ac leo eu ligula ullamcorper sodales at non lacus.', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Accent description' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_accent_description', array(
        'label' => __('Description', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'settings' => 'theme_accent_description',
        'priority' => 3
    )));
    $wp_customize->add_setting('theme_accent_animation_left', array(
        'default' => 'fadeInLeft',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_accent_animation_left', array(
        'label' => __('Content animation', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 4,
    ));
    /*
     * Add the 'accent button label' setting. 
     */
    $wp_customize->add_setting('theme_accent_button_label', array(
        'default' => __('read more', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));

    /*
     * Add the upload control for the 'Accent button label' setting.
     */
    $wp_customize->add_control('theme_accent_button_label', array(
        'label' => __('Button label', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'settings' => 'theme_accent_button_label',
        'priority' => 5
    ));
    /*
     * Add the 'Accent button url' setting. 
     */
    $wp_customize->add_setting('theme_accent_button_url', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'Accent button url' setting.
     */
    $wp_customize->add_control('theme_accent_button_url', array(
        'label' => __('Button url', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'settings' => 'theme_accent_button_url',
        'priority' => 6
    ));
    $wp_customize->add_setting('theme_accent_animation_right', array(
        'default' => 'fadeInRight',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_accent_animation_right', array(
        'label' => __('Button animation', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 7,
    ));
    /*
     * Add section position
     */
    $wp_customize->add_setting('theme_accent_position', array(
        'default' => 80,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_position'
    ));
    $wp_customize->add_control('theme_accent_position', array(
        'label' => __('Section position', 'emmet-lite'),
        'section' => 'theme_accent_section',
        'settings' => 'theme_accent_position',
        'priority' => 30,
    ));
    /*
     * Add the 'subscribe section'. 
     */
    $wp_customize->add_section(
            'theme_subscribe_section', array(
        'title' => __('Subscribe Section', 'emmet-lite'),
        'priority' => 88,
        'capability' => 'edit_theme_options',
        'description' => __('<i>Fill in this section by adding widgets to "Customize > Widgets > Subscribe section"</i><hr/>', 'emmet-lite')
            )
    );

    /*
     * Add the 'Hide faetures section?' setting. 
     */
    $wp_customize->add_setting('theme_subscribe_show', array(
        'default' => 0,
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     *  Add the upload control for the 'subscribe title section?' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_subscribe_show', array(
        'label' => esc_html__('Hide this section', 'emmet-lite'),
        'section' => 'theme_subscribe_section',
        'settings' => 'theme_subscribe_show',
        'type' => 'checkbox',
        'priority' => 1,
            ))
    );
    /*
     * Add the 'subscribe' setting. 
     */
    $wp_customize->add_setting('theme_subscribe_title', array(
        'default' => __('newsletter form', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'subscribe' setting.
     */
    $wp_customize->add_control('theme_subscribe_title', array(
        'label' => __('Title', 'emmet-lite'),
        'section' => 'theme_subscribe_section',
        'settings' => 'theme_subscribe_title',
        'priority' => 2
    ));

    /*
     * Add the 'subscribe' setting. 
     */
    $wp_customize->add_setting('theme_subscribe_description', array(
        'default' => __('Use this section to display subscription form of any WordPress plugin or newsletter service', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'subscribe' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_subscribe_description', array(
        'label' => __('Description', 'emmet-lite'),
        'section' => 'theme_subscribe_section',
        'settings' => 'theme_subscribe_description',
        'priority' => 3
    )));
    $wp_customize->add_setting('theme_subscribe_animation_description', array(
        'default' => 'fadeInLeft',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_subscribe_animation_description', array(
        'label' => __('Description animation', 'emmet-lite'),
        'section' => 'theme_subscribe_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 4,
    ));
    $wp_customize->add_setting('theme_subscribe_animation', array(
        'default' => 'fadeInRight',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_subscribe_animation', array(
        'label' => __('Widgets animation', 'emmet-lite'),
        'section' => 'theme_subscribe_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 5,
    ));
     /*
     * Add section position
     */
    $wp_customize->add_setting('theme_subscribe_position', array(
        'default' => 100,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_position'
    ));
    $wp_customize->add_control('theme_subscribe_position', array(
        'label' => __('Section position', 'emmet-lite'),
        'section' => 'theme_subscribe_section',
        'settings' => 'theme_subscribe_position',
        'priority' => 30,
    ));
    /*
     * Add the 'lastnews section'. 
     */
    $wp_customize->add_section(
            'theme_lastnews_section', array(
        'title' => __('Latest News Section', 'emmet-lite'),
        'priority' => 90,
        'capability' => 'edit_theme_options',
        'description' => __('<i>Automatically filled by your latest posts.</i><hr/>', 'emmet-lite')
            )
    );

    /*
     * Add the 'Hide lastnews section?' setting. 
     */
    $wp_customize->add_setting('theme_lastnews_show', array(
        'default' => 0,
        'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
    ));
    /*
     *  Add the upload control for the 'lastnews title section?' setting.
     */
    $wp_customize->add_control(
            new WP_Customize_Control(
            $wp_customize, 'theme_lastnews_show', array(
        'label' => esc_html__('Hide this section', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'settings' => 'theme_lastnews_show',
        'type' => 'checkbox',
        'priority' => 1,
            ))
    );
    /*
     * Add the 'lastnews' setting. 
     */
    $wp_customize->add_setting('theme_lastnews_title', array(
        'default' => __('blog posts', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'lastnews' setting.
     */
    $wp_customize->add_control('theme_lastnews_title', array(
        'label' => __('Title', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'settings' => 'theme_lastnews_title',
        'priority' => 2
    ));

    /*
     * Add the 'lastnews' setting. 
     */
    $wp_customize->add_setting('theme_lastnews_description', array(
        'default' => __('Keep in touch with the all the latest news and events', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'lastnews' setting.
     */
    $wp_customize->add_control(new MP_Emmet_Theme_Customize_Textarea_Control($wp_customize, 'theme_lastnews_description', array(
        'label' => __('Description', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'settings' => 'theme_lastnews_description',
        'priority' => 3
    )));
    $wp_customize->add_setting('theme_lastnews_animation_description', array(
        'default' => 'fadeInRight',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_lastnews_animation_description', array(
        'label' => __('Description animation', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 4,
    ));
    $wp_customize->add_setting('theme_lastnews_animation', array(
        'default' => 'fadeInLeft',
        'sanitize_callback' => 'mp_emmet_sanitize_animation'
    ));
    $wp_customize->add_control('theme_lastnews_animation', array(
        'label' => __('Posts animation', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'type' => 'select',
        'choices' => mp_emmet_array_animation(),
        'priority' => 5,
    ));
    /*
     * Add the 'install brand button label' setting. 
     */
    $wp_customize->add_setting('theme_lastnews_button_label', array(
        'default' => __('view all posts', 'emmet-lite'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'last news button label' setting.
     */
    $wp_customize->add_control('theme_lastnews_button_label', array(
        'label' => __('Button label', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'settings' => 'theme_lastnews_button_label',
        'priority' => 6
    ));
    /*
     * Add the 'last news button url' setting. 
     */
    $wp_customize->add_setting('theme_lastnews_button_url', array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_text',
        'transport' => 'postMessage'
    ));
    /*
     * Add the upload control for the 'last news button url' setting.
     */
    $wp_customize->add_control('theme_lastnews_button_url', array(
        'label' => __('Button url', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'settings' => 'theme_lastnews_button_url',
        'priority' => 7
    ));
    /*
     * Add section position
     */
    $wp_customize->add_setting('theme_lastnews_position', array(
        'default' => 110,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mp_emmet_sanitize_position'
    ));
    $wp_customize->add_control('theme_lastnews_position', array(
        'label' => __('Section position', 'emmet-lite'),
        'section' => 'theme_lastnews_section',
        'settings' => 'theme_lastnews_position',
        'priority' => 30,
    ));
}

function theme_sanitize_pro_version( $input ) {
  return $input;
  }

/**
 * Sanitize Integer
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function mp_emmet_sanitize_integer($int) {
    if (is_numeric($int)) {
        return intval($int);
    }
}

/**
 * Sanitize text
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function mp_emmet_sanitize_text($txt) {
    return wp_kses_post(force_balance_tags($txt));
}

/**
 * Sanitize checkbox
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function mp_emmet_sanitize_checkbox($input) {
    if ($input == 1) {
        return 1;
    } else {
        return '';
    }
}

/**
 * Sanitize custom image
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function theme_sanitize_custom_image($input) {
    return '';
}

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 *
 * @since Emmet 1.0
 */
function theme_customize_preview_js() {
    wp_enqueue_script('theme-customizer', get_template_directory_uri() . '/js/theme-customizer.min.js', array('customize-preview'), '', true);
}

add_action('customize_preview_init', 'theme_customize_preview_js');

/**
 * Binds JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 * @since Emmet 1.0
 */
function theme_customize_control_js() {
    wp_enqueue_script('color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.min.js', array('customize-controls', 'iris', 'underscore', 'wp-util'), '20141216', true);
    wp_localize_script('color-scheme-control', 'colorScheme', mp_emmet_get_color_schemes());
}

add_action('customize_controls_enqueue_scripts', 'theme_customize_control_js');

/**
 * Register color schemes for Emmet.
 *
 * Can be filtered with {@see 'theme_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Main Background Color.
 * 2. Sidebar Background Color.
 * 3. Box Background Color.
 * 4. Main Text and Link Color.
 * 5. Sidebar Text and Link Color.
 * 6. Meta Box Background Color.
 *
 * @since Emmet 1.0
 *
 * @return array An associative array of color scheme options.
 */
function mp_emmet_get_color_schemes() {
    return apply_filters('theme_color_schemes', array(
        'default' => array(
            'label' => __('Green', 'emmet-lite'),
            'colors' => array(
                MP_EMMET_BRAND_COLOR,
                MP_EMMET_LINK_HOVER_COLOR,
                MP_EMMET_MENU_HOVER_COLOR,
                get_template_directory_uri() . '/images/headers/logo.png',
            ),
        ),
        'blue' => array(
            'label' => __('Blue', 'emmet-lite'),
            'colors' => array(
                '#3a9ad9',
                '#53bbf4',
                '#296f9d',
                get_template_directory_uri() . '/images/headers/logo_blue.png',
            ),
        ),
        'red' => array(
            'label' => __('Red', 'emmet-lite'),
            'colors' => array(
                '#e96656',
                '#e74c3c',
                '#c14234',
                get_template_directory_uri() . '/images/headers/logo_red.png',
            ),
        ),
        'orange' => array(
            'label' => __('Orange', 'emmet-lite'),
            'colors' => array(
                '#e98d56',
                '#e77e3f',
                '#9a5c36',
                get_template_directory_uri() . '/images/headers/logo_orange.png',
            ),
        )
    ));
}

if (!function_exists('mp_emmet_get_color_scheme')) :

    /**
     * Get the current Emmet color scheme.
     *
     * @since Emmet 1.0
     *
     * @return array An associative array of either the current or default color scheme hex values.
     */
    function mp_emmet_get_color_scheme() {
        $theme_color_scheme_option = get_theme_mod('theme_color_scheme', 'default');
        $theme_color_schemes = mp_emmet_get_color_schemes();

        if (array_key_exists($theme_color_scheme_option, $theme_color_schemes)) {
            return $theme_color_schemes[$theme_color_scheme_option]['colors'];
        }
        return $theme_color_schemes['default']['colors'];
    }

endif; // mp_emmet_get_color_scheme


if (!function_exists('mp_emmet_get_color_scheme_choices')) :

    /**
     * Returns an array of color scheme choices registered for Emmet.
     *
     * @since Emmet 1.0
     *
     * @return array Array of color schemes.
     */
    function mp_emmet_get_color_scheme_choices() {
        $theme_color_schemes = mp_emmet_get_color_schemes();
        $theme_color_scheme_control_options = array();

        foreach ($theme_color_schemes as $theme_color_scheme => $value) {
            $theme_color_scheme_control_options[$theme_color_scheme] = $value['label'];
        }
        return $theme_color_scheme_control_options;
    }

endif; // mp_emmet_get_color_scheme_choices

if (!function_exists('mp_emmet_sanitize_color_scheme')) :

    /**
     * Sanitization callback for color schemes.
     *
     * @since Emmet 1.0
     *
     * @param string $value Color scheme name value.
     * @return string Color scheme name.
     */
    function mp_emmet_sanitize_color_scheme($value) {
        $theme_color_schemes = mp_emmet_get_color_scheme_choices();

        if (!array_key_exists($value, $theme_color_schemes)) {
            $value = 'default';
        }

        return $value;
    }

endif; // mp_emmet_sanitize_color_scheme

if (!function_exists('mp_emmet_array_animation')) :

    /**
     * Sanitization callback for color schemes.
     *
     * @since Emmet 1.3.2
     *
     * @param string $value Color scheme name value.
     * @return string Color scheme name.
     */
    function mp_emmet_array_animation() {
        $animation = array(
            'none' => __('None', 'emmet-lite'),
            'fadeIn' => __('Fade In', 'emmet-lite'),
            'fadeInLeft' => __('Fade In Left', 'emmet-lite'),
            'fadeInRight' => __('Fade In Right', 'emmet-lite'),
            'fadeInUp' => __('Fade In Up', 'emmet-lite'),
            'fadeInDown' => __('Fade In Down', 'emmet-lite'),
        );

        return $animation;
    }

endif; // mp_emmet_array_animation
if (!function_exists('mp_emmet_sanitize_animation')) :

    /**
     * Sanitization callback for color schemes.
     *
     * @since Emmet 1.0
     *
     * @param string $value Color scheme name value.
     * @return string Color scheme name.
     */
    function mp_emmet_sanitize_animation($value) {
        $animation = mp_emmet_array_animation();

        if (!array_key_exists($value, $animation)) {
            $value = 'none';
        }

        return $value;
    }

endif; // mp_emmet_sanitize_animation

function mp_emmet_choice_callback($control) {
    $radio_setting = $control->manager->get_setting('theme_bigtitle_radio')->value();
    $control_id = $control->id;

    if (($control_id == 'theme_bigtitle_brandbutton_url' || $control_id == 'theme_bigtitle_brandbutton_label' || $control_id == 'theme_bigtitle_whitebutton_url' || $control_id == 'theme_bigtitle_whitebutton_label' || $control_id == 'theme_bigtitle_title' || $control_id == 'theme_bigtitle_description') && $radio_setting == 'd') {
        return true;
    }
    if ($control_id == 'theme_mp_slider' && $radio_setting == 's') {
        return true;
    }

    return false;
}

function mp_emmet_sanitize_radio($input) {
    return $input;
}

if (!function_exists('mp_emmet_blog_list')) :

    /**
     * Returns an array of blog style registered for Emmet.
     *
     * @since Emmet 
     *
     * @return array Array of blog style.
     */
    function mp_emmet_blog_list() {
        $mp_emmet_blog_list = array(
            'default' => __('With Sidebar', 'emmet-lite'),
            'masonry' => __('Masonry', 'emmet-lite'),
            'full-width' => __('Full Width', 'emmet-lite'),
            'two-columns' => __('Two Columns', 'emmet-lite')
        );
        return $mp_emmet_blog_list;
    }

endif; // mp_emmet_blog_list

if (!function_exists('mp_emmet_sanitize_position')) :

    /**
     * Sanitize position
     *
     * @since  1.0.1
     * @access public
     * @return sanitized output
     */
    function mp_emmet_sanitize_position($str) {
        if (mp_emmet_is_positive_integer($str)) {
            return intval($str);
        }
    }

endif;
if (!function_exists('mp_emmet_is_positive_integer')) :

    /**
     * Sanitize is positive integer
     *
     * @since  1.0.1
     * @access public
     * @return sanitized output
     */
    function mp_emmet_is_positive_integer($str) {
        return (is_numeric($str) && $str > 0 && $str == round($str));
    }
endif;