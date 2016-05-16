<?php
define('MP_EMMET_DEFAULT_PHONE', __('Phone: <b>123-456-7890</b>', 'emmet-lite'));
define('MP_EMMET_DEFAULT_ADDRESS', __('Address: <b>123 Street W, Seattle WA 99999</b>', 'emmet-lite'));
define('MP_EMMET_TEXT_COLOR', '#555555');
define('MP_EMMET_BRAND_COLOR', '#27b399');
define('MP_EMMET_LINK_HOVER_COLOR', '#37c4aa');
define('MP_EMMET_MENU_HOVER_COLOR', '#1a967f');
/*
 * Set up the content width value based on the theme's design.
 *
 */
if (!isset($content_width))
    $content_width = 770;

/**
 * Add support for a custom header image.
 */
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/admin/customize.php';


/*
 * emmet only works in WordPress 3.6 or later.
 */
if (version_compare($GLOBALS['wp_version'], '3.6-alpha', '<'))
    require get_template_directory() . '/inc/back-compat.php';

/**
 * emmet setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * emmet supports.
 *
 * @since emmet 1.0
 */
function mp_emmet_setup() {
    /*
     * This theme styles the visual editor to resemble the theme style,
     * specifically font, colors, icons, and column width.
     */
    add_editor_style();
    /*
     * Makes emmet available for translation.
     *
     * Translations can be added to the /languages/ directory.
     * If you're building a theme based on emmet, use a find and
     * replace to change 'emmet' to the name of your theme in all
     * template files.
     */
    load_theme_textdomain('emmet-lite', get_template_directory() . '/languages');

    /*
     *  Adds RSS feed links to <head> for posts and comments.
     */
    add_theme_support('automatic-feed-links');
    /*
     * Supporting title tag via add_theme_support (since WordPress 4.1)
     */
    add_theme_support('title-tag');
    /*
     * This theme supports a variety of post formats.
     */
    add_theme_support('post-formats', array('aside', 'gallery', 'image', 'video', 'quote', 'audio', 'link', 'status',));
    /*
     *  This theme uses wp_nav_menu() in one location.
     */

    register_nav_menus(
            array(
                'primary' => __('Primary Menu', 'emmet-lite'),
                'top-menu' => __('Top Menu', 'emmet-lite')
    ));

    /*
     * This theme uses its own gallery styles.
     */
    add_filter('use_default_gallery_style', '__return_false');

    /*
     * Add theme support post thumbnails.
     */

    if (function_exists('add_theme_support')) {
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(750, 375, true);
    }
    add_image_size('mp-emmet-thumb-thumbnail', 170, 170, true);
    add_image_size('mp-emmet-thumb-medium-masonry', 720, 9999, false);
    add_image_size('mp-emmet-thumb-large', 1140, 350, true);
    add_image_size('mp-emmet-thumb-medium', 265, 260, true);
}

add_action('after_setup_theme', 'mp_emmet_setup');
/*
 * Emmet admin js.
 *
 * Add js for customizer.
 *
 * @since emmet 1.3.2
 */

function mp_emmet_enqueue() {
    if (is_callable('is_customize_preview') && is_customize_preview()) {
        wp_enqueue_script('theme-sections', get_template_directory_uri() . '/js/theme-sections.min.js');
    }
}

add_action('admin_enqueue_scripts', 'mp_emmet_enqueue');

/**
 * Emmet page menu.
 *
 * Show pages of site.
 *
 * @since emmet 1.0
 */
function mp_emmet_wp_page_menu() {
    echo '<ul class="sf-menu">';
    wp_list_pages(array('title_li' => '', 'depth' => 1));
    echo '</ul>';
}

/**
 * Emmet page top menu.
 *
 * Show pages of site.
 *
 * @since emmet 1.0
 */
function mp_emmet_wp_page_short_menu() {
    echo'<ul id="menu-top-menu" class="menu">';
    $pages = wp_list_pages(array('title_li' => '', 'depth' => 1, 'echo' => 0));
    $pages = explode("</li>", $pages);
    $count = 0;
    foreach ($pages as $page) {
        $count++;
        echo $page;
        if ($count == 3) {
            break;
        }
    }
    echo '</ul>';
}

/**
 * Emmet Mobile Menu
 *
 *  Show menu or pages of site.
 * 
 * @since emmet 1.0
 */
require get_template_directory() . '/inc/theme/nav-menu-dropdown.php';

function mp_emmet_mobile_menu() {
    if (has_nav_menu('primary')) :
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'walker' => new MP_Emmet_Walker_Nav_Menu_Dropdown(),
            'items_wrap' => '<div class="mobile-menu "><form name="menu-form" id="menu-form" action="#" method="post" get=""><div class="select-wrapper"><select onchange="if (this.value) window.location.href=this.value">%3$s</select></div></form></div>',
        ));
    else:
        echo '<div class="mobile-menu "><div class="select-wrapper"><select name="page-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;"> ';
//        echo '<option value="">'. _e('Select page', 'emmet-lite') .'</option>';
        $pages = get_pages();
        global $post;
        foreach ($pages as $page) {
            $option = '<option ';
            if ($page->ID === $post->ID):
                $option .='selected ';
            endif;
            $option .='value="' . get_page_link($page->ID) . '">';
            $option .= $page->post_title;
            $option .= '</option>';
            echo esc_html($option);
        }
        echo '</select></div></div>';
    endif;
}

function mp_emmet_before_header() {
    do_action('theme_before_header');
}

add_action('theme_before_header', 'be_mobile_menu');

/* Return the Google font stylesheet URL, if available.
 *
 * The use of Open Sans by default is localized. 
 *
 * @since  1.0.0
 * @access public
 * @return void
 */

function mp_emmet_load_google_fonts() {
    wp_register_style('googleOpenSans', '//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,700,700italic&subset=latin,cyrillic');
    wp_enqueue_style('googleOpenSans');
}

add_action('wp_enqueue_scripts', 'mp_emmet_load_google_fonts');

/**
 * Enqueue scripts and styles for the front end.
 */
function mp_emmet_scripts_styles() {
    /*
     * Adds JavaScript to pages with the comment form to support
     * sites with threaded comments (when in use).
     */
    if (is_singular() && comments_open() && get_option('thread_comments'))
        wp_enqueue_script('comment-reply');
    /*
     *  Scripts for template masonry blog
     */
    $mp_emmet_blog_type = esc_html(get_theme_mod('theme_blog_style', 'default'));
    if (is_home() && $mp_emmet_blog_type === 'masonry') {
        wp_enqueue_script('jquery-masonry');
        wp_enqueue_script('jquery.infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array('jquery'), '2.1.0', true);
    }
    wp_enqueue_script('superfish.min', get_template_directory_uri() . '/js/superfish.min.js', array('jquery', 'hoverIntent'), '1.7.5', true);
    wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery', 'hoverIntent'), '2.5.0', true);
    wp_enqueue_script('jquery.appear', get_template_directory_uri() . '/js/jquery.appear.min.js', array('jquery', 'hoverIntent'), '0.3.6', true);
    wp_enqueue_script('emmet-script', get_template_directory_uri() . '/js/emmet.min.js', array('jquery', 'superfish.min', 'jquery.appear'), '1.0', true);

    $translation_array = array(
        'url' => get_template_directory_uri()
    );
    wp_localize_script('emmet-script', 'template_directory_uri', $translation_array);

    /*
     * Loads Emmet Styles
     */
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.5', 'all');

    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array('bootstrap'), '4.3.0', 'all');

    wp_enqueue_style('flexslider', get_template_directory_uri() . '/css/flexslider.min.css', array('bootstrap'), '2.5.0', 'all');

    wp_enqueue_style('main', get_template_directory_uri() . '/css/emmet-style.min.css', array('bootstrap', 'font-awesome'), '1.0', 'all');

    if (is_plugin_active('motopress-content-editor/motopress-content-editor.php') || is_plugin_active('motopress-content-editor-lite/motopress-content-editor.php')) {
        wp_enqueue_style('emmet-motopress', get_template_directory_uri() . '/css/emmet-motopress.min.css', array('bootstrap', 'font-awesome', 'main'), '1.0', 'all');
    }

    if (is_plugin_active('woocommerce/woocommerce.php')) {
        wp_enqueue_style('emmet-woocommerce', get_template_directory_uri() . '/css/emmet-woocommerce.min.css', array('bootstrap', 'font-awesome', 'main'), '1.0', 'all');
    }

    if (is_plugin_active('bbpress/bbpress.php')) {
        wp_enqueue_style('emmet-bbpress', get_template_directory_uri() . '/css/emmet-bbpress.min.css', array('bootstrap', 'font-awesome', 'main'), '1.0', 'all');
    }

    if (is_plugin_active('buddypress/bp-loader.php')) {
        wp_enqueue_style('emmet-buddypress', get_template_directory_uri() . '/css/emmet-buddypress.min.css', array('bootstrap', 'font-awesome', 'main'), '1.0', 'all');
    }

    if (is_rtl()) {
        wp_enqueue_style('emmet-rtl', get_template_directory_uri() . '/css/emmet-rtl.min.css', array('bootstrap', 'font-awesome', 'main'), '1.0', 'all');
    }

    /*
     *  Loads our main stylesheet.
     */
    wp_enqueue_style('emmet-style', get_stylesheet_uri(), array(), '1.0');
}

add_action('wp_enqueue_scripts', 'mp_emmet_scripts_styles');

/**
 * Title Tag backwards compatibility for older versions
 *
 */
if (!function_exists('_wp_render_title_tag')) {

    function mp_emmet_slug_render_title() {
        ?>
        <?php wp_title('|', true, 'right'); ?>     
        <?php
    }

    add_action('wp_head', 'mp_emmet_slug_render_title');
}

/**
 * Register widget areas.
 *
 * @since emmet 1.0
 * @access public
 * @return void
 */
function mp_emmet_widgets_init() {

    register_sidebar(array(
        'name' => __('Main Widget Area', 'emmet-lite'),
        'id' => 'sidebar-1',
        'description' => __('Appears on posts and pages in the sidebar.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer Left', 'emmet-lite'),
        'id' => 'sidebar-2',
        'description' => __('Appears in the footer section of the site.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => __('Footer Center', 'emmet-lite'),
        'id' => 'sidebar-3',
        'description' => __('Appears in the footer section of the site.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => __('Footer Right', 'emmet-lite'),
        'id' => 'sidebar-4',
        'description' => __('Appears in the footer section of the site.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => __('404 Widget Area', 'emmet-lite'),
        'id' => 'sidebar-404',
        'description' => __('Appears on 404 page in the sidebar.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-xs-12 col-sm-4 col-md-4 col-lg-4">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title h2">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Features Section', 'emmet-lite'),
        'id' => 'sidebar-features',
        'description' => __('Appears on front page.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-xs-12 col-sm-12 col-md-12 col-lg-12">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Packages Section', 'emmet-lite'),
        'id' => 'sidebar-plan',
        'description' => __('Appears on front page.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-xs-12 col-sm-12 col-md-12 col-lg-12">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Team Section', 'emmet-lite'),
        'id' => 'sidebar-team',
        'description' => __('Appears on front page.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-xs-12 col-sm-12 col-md-12 col-lg-12">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Subscribe Section', 'emmet-lite'),
        'id' => 'sidebar-subscribe',
        'description' => __('Appears on front page.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Testimonials Section', 'emmet-lite'),
        'id' => 'sidebar-testimonials',
        'description' => __('Appears on front page.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-xs-12 col-sm-12 col-md-12 col-lg-12">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Google Map Section', 'emmet-lite'),
        'id' => 'sidebar-googlemap',
        'description' => __('Appears on front page.', 'emmet-lite'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
}

add_action('widgets_init', 'mp_emmet_widgets_init');

/*
 * Post comments
 */

function mp_emmet_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <div class="comment-description">
            <?php endif; ?>
            <div class="comment-author vcard">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>            
            </div>
            <div class="comment-content">
                <?php printf('<h4 class="fn">%s</h4>', get_comment_author_link()); ?>
                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'emmet-lite'); ?></em>
                    <br />
                <?php endif; ?>
                <div class="comment-meta commentmetadata date-post h6">
                    <?php
                    printf(__('%1$s <span>at %2$s</span>', 'emmet-lite'), get_comment_date('F j, Y'), get_comment_time());
                    ?>
                    <?php edit_comment_link(__('(Edit)', 'emmet-lite'), '  ', ''); ?>
                </div>
                <?php comment_text(); ?>

                <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>
                <?php if ('div' != $args['style']) : ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
}

/*
 * Post meta
 */

function mp_emmet_post_meta($post) {
    ?>
    <?php if (get_theme_mod('theme_show_meta', '1') === '1' || get_theme_mod('theme_show_meta') || get_theme_mod('theme_show_tags', '1') === '1' || get_theme_mod('theme_show_tags') || get_theme_mod('theme_show_categories', '1') === '1' || get_theme_mod('theme_show_categories')): ?>
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
                    <?php if (get_theme_mod('theme_show_tags', '1') === '1' || get_theme_mod('theme_show_tags')): ?>                        
                        <?php the_tags('<span class="seporator">/</span> <span>' . __('Tagged with', 'emmet-lite') . '</span> ', '<span>,</span> ', ''); ?> 
                    <?php endif; ?>
                    <?php if (get_theme_mod('theme_show_categories', '1') === '1' || get_theme_mod('theme_show_categories')): ?>
                        <?php $categories = get_the_category_list('<span>,</span> ', 'multiple', $post->ID); ?>
                        <?php if (!empty($categories)) : ?>
                            <span class="seporator">/</span>                         
                            <span><?php _e('Posted in', 'emmet-lite'); ?></span> 
                            <?php echo $categories; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php edit_post_link(__('Edit', 'emmet-lite'), '<span class="seporator">/</span> ', ''); ?>
                </div>   
            <?php endif; ?>
        </footer> 
        <?php
    endif;
}

/*
 * Post thumbnail 
 */

function mp_emmet_post_thumbnail($post, $emmetPageTemplate) {
    ?>
    <?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) : ?>
        <div class="entry-thumbnail">
            <?php if ($emmetPageTemplate == 'full-width'): ?>
                <a href = "<?php the_permalink(); ?>" ><?php the_post_thumbnail('mp-emmet-thumb-large'); ?></a>
            <?php else: ?>
                <?php if (!is_single()) { ?>
                    <a href = "<?php the_permalink(); ?>" ><?php the_post_thumbnail(); ?></a>                
                <?php } else { ?>
                    <?php the_post_thumbnail(); ?>
                <?php } ?>
            <?php endif; ?>
        </div>
    <?php else:
        ?>
        <?php if ($emmetPageTemplate == 'two-columns'): ?>
            <div class="entry-thumbnail empty-entry-thumbnail">
                <a href = "<?php the_permalink(); ?>" rel="external" title="<?php the_title(); ?>"><span class="date-post">
                        <?php echo get_post_time('j M'); ?>
                    </span></a>
            </div> 
        <?php endif; ?>
    <?php
    endif;
}

/*
 * The experts length 
 */

function mp_emmet_excerpt_length($length) {
    return 13;
}

add_filter('excerpt_length', 'mp_emmet_excerpt_length', 999);

/*
 * Emmet breadcrumbs
 */
require get_template_directory() . '/inc/theme/breadcrumbs.php';

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
/*
 * Declare WooCommerce support
 */
add_action('after_setup_theme', 'mp_emmet_woocommerce_support');

function mp_emmet_woocommerce_support() {
    add_theme_support('woocommerce');
}

/*
 * Init woocommerce
 */

if (is_plugin_active('woocommerce/woocommerce.php')) {
    require get_template_directory() . '/inc/woocommerce/woo-init.php';
}

/*
 * Init motopress
 */
if (is_plugin_active('motopress-content-editor/motopress-content-editor.php') || is_plugin_active('motopress-content-editor-lite/motopress-content-editor.php')) {
    require get_template_directory() . '/inc/motopress/motopress-init.php';
}
/*
 * Init bbpress
 */
if (is_plugin_active('bbpress/bbpress.php')) {
    require get_template_directory() . '/inc/bbpress/bbpress-init.php';
}

/*
 * hook sections of front page
 */
require get_template_directory() . '/inc/theme/sections-functions.php';
require get_template_directory() . '/inc/theme/sections-hooks.php';

function mp_emmet_get_first_embed_media($post_id) {

    $post = get_post($post_id);
    $content = do_shortcode(apply_filters('the_content', $post->post_content));
    $embeds = get_media_embedded_in_content($content);
    if (!empty($embeds)) {
        //return first embed
        return '<div class="entry-media">' . $embeds[0] . '</div>';
    } else {
        //No embeds found
        return false;
    }
}

function mp_emmet_get_content_theme($contentLength, $addP) {
    ?>
    <?php
    $content = apply_filters('the_content', get_the_content());
    if ($addP) {
        $content = strip_tags($content, '<p>');
        $content = wp_kses($content, array('p' => array()));
    } else {
        $content = wp_strip_all_tags($content);
        $content = wp_kses($content, array());
    }

    $content = preg_replace('/<(script|style)(.*?)>(.*?)<\/(script|style)>/is', '', $content);
    if (strlen($content) > $contentLength) {
        $content = extension_loaded('mbstring') ? mb_substr($content, 0, $contentLength) . '...' : substr($content, 0, $contentLength) . '...';
    }
    echo $content;
    ?>
    <?php
}

function mp_emmet_get_post_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if ($output === 0) {
        return $first_img;
    }
    $first_img = $matches[1][0];
    if (empty($first_img)) {
        $first_img = "";
    }
    return $first_img;
}

add_filter('wp_audio_shortcode', 'mp_emmet_audio_short_fix', 10, 5);

function mp_emmet_audio_short_fix($html, $atts, $audio, $post_id, $library) {
    $html = str_replace('visibility: hidden;', '', $html);
    return $html;
}

require get_template_directory() . '/inc/theme/tgm-init.php';


/*
 * Theme Wizard admin notice 
 */

function mp_emmet_wizard_admin_notice() {
    $currentScreen = get_current_screen();
    if($currentScreen->id != "themes"){
        return;        
    }
    mp_emmet_wizard_dismiss();
    $isThemeActivation = apply_filters('mp_emmet_activation', true);
    if ($isThemeActivation && !get_user_meta(get_current_user_id(), 'mp_emmet_wizard_dismiss', true)) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong><?php _e('You&#8217;ve installed Emmet theme. Click &#34;Run Theme Wizard&#34; to view a quick guided tour of theme functionality and complete the installation.', 'emmet-lite'); ?></strong></p>
            <p><a class="button button-primary" href="<?php echo esc_url(home_url('/wp-admin/admin.php?page=theme-setup'));?>"><strong><?php _e('Run Theme Wizard', 'emmet-lite'); ?><strong></a> <a class="button"  href="/wp-admin/themes.php?mp-emmet-wizard-dismiss=dismiss_admin_notices" class="dismiss-notice" target="_parent"><strong><?php _e('Skip', 'emmet-lite'); ?><strong></a></p>
        </div>
        <?php
    }
}

add_action('admin_notices', 'mp_emmet_wizard_admin_notice');
/*
 * Dismiss Theme Wizard admin notice 
 */

function mp_emmet_wizard_dismiss() {
    if (isset($_GET['mp-emmet-wizard-dismiss'])) {
        update_user_meta(get_current_user_id(), 'mp_emmet_wizard_dismiss', 1);
    }
}

/*
 * Activate theme 
 */
require get_template_directory() . '/classes/theme/class-theme-install.php';


