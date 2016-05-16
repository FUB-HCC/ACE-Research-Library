<?php  
/**
 * Zeroerror Lite functions and definitions
 *
 * @package Zeroerror Lite
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

if ( ! function_exists( 'zeroerror_lite_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function zeroerror_lite_setup() {   
	if ( ! isset( $content_width ) )
		$content_width = 640; /* pixels */

	load_theme_textdomain( 'zeroerror-lite', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('woocommerce');
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'title-tag' );
	add_image_size('zeroerror-lite-homepage-thumb',240,145,true);
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'zeroerror-lite' ),
		'footer' => __( 'Footer Menu', 'zeroerror-lite' ),
	) );
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff'
	) );
	add_editor_style( 'editor-style.css' );
} 
endif; // zeroerror_lite_setup
add_action( 'after_setup_theme', 'zeroerror_lite_setup' );

// Set the word limit of post content 
function zeroerror_lite_content($limit) {
$content = explode(' ', get_the_content(), $limit);
if (count($content)>=$limit) {
array_pop($content);
$content = implode(" ",$content).'...';
} else {
$content = implode(" ",$content);
}	
$content = preg_replace('/\[.+\]/','', $content);
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);
return $content;
}

function zeroerror_lite_widgets_init() { 	
	
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'zeroerror-lite' ),
		'description'   => __( 'Appears on blog page sidebar', 'zeroerror-lite' ),
		'id'            => 'sidebar-1',
		'before_widget' => '',		
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
	) );	
	
}
add_action( 'widgets_init', 'zeroerror_lite_widgets_init' );


function zeroerror_lite_font_url(){
		$font_url = '';		
		
		/* Translators: If there are any character that are not
		* supported by raleway, trsnalate this to off, do not
		* translate into your own language.
		*/
		$raleway = _x('on','raleway:on or off','zeroerror-lite');		
		
		
		/* Translators: If there has any character that are not supported 
		*  by Scada, translate this to off, do not translate
		*  into your own language.
		*/
		$scada = _x('on','Scada:on or off','zeroerror-lite');	
		
		if('off' !== $raleway ){
			$font_family = array();
			
			if('off' !== $raleway){
				$font_family[] = 'raleway:300,400,600,700,800,900';
			}
					
						
			$query_args = array(
				'family'	=> urlencode(implode('|',$font_family)),
			);
			
			$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
		}
		
	return $font_url;
	}


function zeroerror_lite_scripts() {
	wp_enqueue_style('zeroerror-lite-font', zeroerror_lite_font_url(), array());
	wp_enqueue_style( 'zeroerror-lite-basic-style', get_stylesheet_uri() );
	wp_enqueue_style( 'zeroerror-lite-editor-style', get_template_directory_uri()."/editor-style.css" );
	wp_enqueue_style( 'zeroerror-lite-nivoslider-style', get_template_directory_uri()."/css/nivo-slider.css" );
	wp_enqueue_style( 'zeroerror-lite-main-style', get_template_directory_uri()."/css/responsive.css" );		
	wp_enqueue_style( 'zeroerror-lite-base-style', get_template_directory_uri()."/css/default.css" );
	wp_enqueue_script( 'zeroerror-lite-nivo-script', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery') );
	wp_enqueue_script( 'zeroerror-lite-custom_js', get_template_directory_uri() . '/js/custom.js' );
	wp_enqueue_style( 'zeroerror-lite-animation-style', get_template_directory_uri()."/css/animation.css" );	
	wp_enqueue_style( 'zeroerror-lite-font-awesome-style', get_template_directory_uri()."/css/font-awesome.css" );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zeroerror_lite_scripts' );

function zeroerror_lite_ie_stylesheet(){
	global $wp_styles;
	
	/** Load our IE-only stylesheet for all versions of IE.
	*   <!--[if lt IE 9]> ... <![endif]-->
	*
	*  Note: It is also possible to just check and see if the $is_IE global in WordPress is set to true before
	*  calling the wp_enqueue_style() function. If you are trying to load a stylesheet for all browsers
	*  EXCEPT for IE, then you would HAVE to check the $is_IE global since WordPress doesn't have a way to
	*  properly handle non-IE conditional comments.
	*/
	wp_enqueue_style('zeroerror-lite-ie', get_template_directory_uri().'/css/ie.css', array('zeroerror-lite-style'));
	$wp_styles->add_data('zeroerror-lite-ie','conditional','IE');
	}
add_action('wp_enqueue_scripts','zeroerror_lite_ie_stylesheet');


function zeroerror_lite_pagination() {
	global $wp_query;
	$big = 12345678;
	$page_format = paginate_links( array(
	    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	    'format' => '?paged=%#%',
	    'current' => max( 1, get_query_var('paged') ),
	    'total' => $wp_query->max_num_pages,
	    'type'  => 'array'
	) );
	if( is_array($page_format) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		echo '<div class="pagination"><div><ul>';
		echo '<li><span>'. $paged . ' of ' . $wp_query->max_num_pages .'</span></li>';
		foreach ( $page_format as $page ) {
			echo "<li>$page</li>";
		}
		echo '</ul></div></div>';
	}
}


define('GRC_URL','http://www.gracethemes.com','zeroerror-lite');
define('GRC_THEME_DOC','http://gracethemes.com/documentation/zeroerror/','zeroerror-lite');
define('GRC_PRO_THEME_URL','http://gracethemes.com/themes/zeroerror-business-wordpress-theme/','zeroerror-lite');
define('GRC_LIVE_DEMO','http://gracethemes.com/demo/zeroerror/','zeroerror-lite');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template for about theme.
 */
require get_template_directory() . '/inc/about-themes.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



function zeroerror_lite_custom_blogpost_pagination( $wp_query ){
	$big = 999999999; // need an unlikely integer
	if ( get_query_var('paged') ) { $pageVar = 'paged'; }
	elseif ( get_query_var('page') ) { $pageVar = 'page'; }
	else { $pageVar = 'paged'; }
	$pagin = paginate_links( array(
		'base' 			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' 		=> '?'.$pageVar.'=%#%',
		'current' 		=> max( 1, get_query_var($pageVar) ),
		'total' 		=> $wp_query->max_num_pages,
		'prev_text'		=> '&laquo; Prev',
		'next_text' 	=> 'Next &raquo;',
		'type'  => 'array'
	) ); 
	if( is_array($pagin) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		echo '<div class="pagination"><div><ul>';
		echo '<li><span>'. $paged . ' of ' . $wp_query->max_num_pages .'</span></li>';
		foreach ( $pagin as $page ) {
			echo "<li>$page</li>";
		}
		echo '</ul></div></div>';
	} 
}
// get slug by id
function zeroerror_lite_get_slug_by_id($id) {
	$post_data = get_post($id, ARRAY_A);
	$slug = $post_data['post_name'];
	return $slug; 
}