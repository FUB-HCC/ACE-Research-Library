<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of MPCEShortcodePostsGrid
 *
 */
class MPCEShortcodePostsGrid {
	
	const SECRET_KEY_OPTION = 'motopress-ce-post-grid-secret-key';

	const FILTER_FIRST_TAXONOMY = 'cats';
	const FILTER_SECOND_TAXONOMY = 'tags';
	const FILTER_BOTH_TAXONOMY = 'both';
	const FILTER_NONE_TAXONOMY = 'none';

	const DISPLAY_TYPE_SHOW_ALL = 'show_all';
	const DISPLAY_TYPE_PAGINATION = 'pagination';
	const DISPLAY_TYPE_LOAD_MORE = 'load_more';

	const QUERY_TYPE_SIMPLE = 'simple';
	const QUERY_TYPE_CUSTOM = 'custom';
	const QUERY_TYPE_IDS = 'ids';

	/**
	 * @var bool Flag to avoid nested posts grids.
	 */
	private static $running = false;
	
	private $attrs = array();
	
	private $uniqueId;
	
	private $pageNumber;

	/**
	 * @var WP_Query 
	 */
	private $query;

	private $shortcodeAttrs;

	private $currentPostId;
	
	/**
	 * @return array Shortcode default attributes.
	 */
	public function getDefaultAttrs() {
		$motopressCELang = motopressCEGetLanguageDict();
		return array(
			'query_type' => 'simple',
			'post_type' =>  'post',
			'columns' => 3,
			'category' => '',
			'tag' => '',
			'posts_per_page' => 3,
			'posts_order' => 'DESC',
			'custom_tax' => '',
			'custom_tax_field' => '',
			'custom_tax_terms' => '',
			'custom_query' => '',
			'ids' => '',
			'template' => 'plugins/motopress-content-editor/includes/ce/shortcodes/post_grid/templates/template1.php',
			'posts_gap' => 30,
			'show_featured_image' => 'true',
			'image_size' => 'large',
			'image_custom_size' => '',
			'title_tag' => 'h2',
			'show_date_comments' => 'true',
			'show_content' => 'short',
			'short_content_length' => 200,
			'read_more_text' => '',
			'display_style' => 'false', // Not "show_all", for compatibility with elder versions
			'pagination' => 'false',
			'load_more_btn' => 'false',
			'load_more_text' => $motopressCELang->CEPostsGridObjLoadMoreTextDefault,
			'filter' => 'none',
			'filter_tax_1' => 'category',
			'filter_tax_2' => 'post_tag',
			'filter_btn_color' => 'motopress-btn-color-silver',
			'filter_btn_divider' => '',
			'filter_cats_text' => '',
			'filter_tags_text' => '',
			'filter_all_text' => '',
//			'show_sticky_posts' => 'false',
		);
	}
	
	public static function generateSecretKey(){
		$secretKey = wp_generate_password(20, true, true);
		update_option(self::SECRET_KEY_OPTION, $secretKey);
		return $secretKey;
	}
	
	public static function getSecretKey() {
		$secretKey = get_option(self::SECRET_KEY_OPTION);
		return ($secretKey !== false) ? $secretKey : self::generateSecretKey();
	}
	
	/**
	 *  Description: Init "Pagination" and "Load More" parameters for compatibility with elder versions
	 * 
	 * @param array $attrs
	 */
	private function fixPaginationParameters(&$attrs){
        if ($attrs['display_style'] == self::DISPLAY_TYPE_SHOW_ALL) {
            $attrs['pagination'] = 'false';
            $attrs['load_more_btn'] = 'false';
        } else if ($attrs['display_style'] == self::DISPLAY_TYPE_PAGINATION) {
            $attrs['pagination'] = 'true';
            $attrs['load_more_btn'] = 'false';
        } else if ($attrs['display_style'] == self::DISPLAY_TYPE_LOAD_MORE) {
            $attrs['pagination'] = 'false';
            $attrs['load_more_btn'] = 'true';
        }
	}
	
	/**
	 * @return array
	 */
	public function getAttrs(){
		return $this->attrs;
	}

	/**
	 *
	 * @return WP_Query
	 */
	public function getQuery(){
		return $this->query;
	}

	/**
	 *
	 * @return int
	 */
	public function getCurrentPostId(){
		return $this->currentPostId;
	}
	
	/**
	 * Get shortcode attribute value by name.
	 * @param string $attrName Attribute name. Accept: query_type, post_type, columns, category,
	 *							tag, posts_per_page, posts_order, custom_tax, custom_tax_field,
	 *							custom_tax_terms, custom_query, template, posts_gap, show_featured_image,
	 *							image_size, image_custom_size, title_tag, show_date_comments, short_content_length
	 *							read_more_text, display_style, pagination, load_more_btn, load_more_text, filter
	 *							filter_tax_1, filter_tax_2, filter_btn_color, filter_btn_divider, filter_cats_text,
	 *							filter_tags_text, show_sticky_posts
	 * @return bool|string The attribute value if the name exists or FALSE.
	 */
	public function getAttr($attrName){
		return isset($this->attrs[$attrName]) ? $this->attrs[$attrName] : false;
	}

	/**
	 *
	 * @param array $shortcodeAttrs
	 */
	public function setAttrs($shortcodeAttrs){
		$attrs = shortcode_atts(
			MPCEShortcode::addStyleAtts(array_merge(self::getDefaultAttrs())),
			$shortcodeAttrs
		);
		$this->fixPaginationParameters($attrs);		
		$attrs['template'] = self::fixTemplatePath($attrs['template']);
		if (MPCEShortcode::isNeedStyleClassesFix() && empty($attrs['mp_style_classes'])) {
            if (!empty($attrs['custom_class'])) $attrs['mp_style_classes'].= ' ' . $attrs['custom_class'];
        }
		$this->attrs = $attrs;
	}
	
	/**
	 * 
	 * @param array $attrs An array of shortcode attrs	 
	 * @param array $additional_args { Optional. Array of arguments.
	 *		@type bool|array $filters Whether to filter posts by specified taxonomies. An array can specify filters where keys are taxonomies and values are terms. Default FALSE.
	 *		@type int $page Number of page. Default 1.
	 *		@type bool|int $post_id Current post id. If FALSE it will be autodetected. 
	 * }
	 * 
	 * @return string
	 */
	public function __construct($attrs, $additional_args = array()) {		
		$additionalArgsDefaults = array(
			'filters' => false,
			'page' => 1,
			'post_id' => false
		);
		$additional_args = array_merge($additionalArgsDefaults, $additional_args);
		$this->shortcodeAttrs = $attrs;
		$this->generateUniqueId();
		$this->setAttrs($attrs);
		
		// Modificate query args
		$this->initPostID($additional_args['post_id']);
		$this->setPageNumber($additional_args['page']);
		$args = $this->getWPQueryArgs();
		if ($additional_args['filters'] !== false) {
			$this->setFilters($additional_args['filters'], $args);
		}
        // Query posts
        $this->query = new WP_Query($args);
	}

	/**
	 * Modify $args tax_query with $filters.
	 *
	 * @param array $filters An array of taxonomy filters where keys are taxonomy names and values are terms.
	 * @param array $args An array of WP_Query args.
	 * 
	 */
	public function setFilters($filters, &$args){
		$tax_query = array();
		foreach ($filters as $tax => $terms) {
			if (!empty($terms)) {
				if (!empty($tax_query)) {
					$tax_query['relation'] = 'AND';
				}
				$tax_query[] = array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms' => $terms
				);
			}
		}
		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		} else {
			unset($args['tax_query']);
		}
	}

	/**
	 *
	 * @param int $pageNumber
	 */
	public function setPageNumber($pageNumber){
		$this->pageNumber = $pageNumber;
	}

	/**
	 * @return int
	 */
	public function getPageNumber(){
		return $this->pageNumber;
	}
	
	public function generateUniqueId() {
		$prefix = 'motopress_posts_grid_';
		$this->uniqueId =  $prefix . uniqid();
	}

	/**
	 * @return string
	 */
	public function getUniqueId() {
		return $this->uniqueId;
	}

	/**
	 * @return array
	 */
	private function getWPQueryTaxonomyArgs(){
		$tax_query = array();
		$post_type = $this->getAttr('post_type');
		$category = $this->getAttr('category');
		$tag = $this->getAttr('tag');

		if ($post_type === 'post') {
			// Categories and post_tags can be specified only for the posts.
			if (!empty($category)) {
				$tax_query_cat = array(
					'taxonomy' => 'category',
					'field' => 'slug'
				);				
				if (strpos($category, '+') !== false && strpos($category, ',') !== false) {
					$cat_regex = '/[+,\s]+/';
				} else if (strpos($category, '+') !== false) {
					$tax_query_cat['operator'] = 'AND';
					$cat_regex = '/[+\s]+/';
				} else {
					$cat_regex = '/[,\s]+/';
				}
				$tax_query_cat['terms'] = array_unique( preg_split( $cat_regex, $category ) );
				$tax_query[] = $tax_query_cat;
			}
			
			if (!empty($tag)) {
				$tax_query_tag = array(
					'taxonomy' => 'post_tag',
					'field' => 'slug'
				);
				if (strpos($tag, '+') !== false && strpos($tag, ',') !== false) {
					$tag_regex = '/[+,\s]+/';
				} else if (strpos($tag, '+') !== false) {
					$tax_query_tag['operator'] = 'AND';
					$tag_regex = '/[+\s]+/';
				} else {
					$tag_regex = '/[,\s]+/';
				}
				$tax_query_tag['terms'] = array_unique( preg_split( $tag_regex, $tag ) );
				$tax_query[] = $tax_query_tag;
			}			
		}
		
		$custom_tax = $this->getAttr('custom_tax');
		$custom_tax_field = $this->getAttr('custom_tax_field');
		$custom_tax_terms = $this->getAttr('custom_tax_terms');		
		if (!empty($custom_tax) && !empty($custom_tax_field) && !empty($custom_tax_terms)) {                    
			$tax_query_defaults = array(
				'taxonomy' => $custom_tax,
				'field' => $custom_tax_field,
			);
			if ( strpos($custom_tax_terms, '+') !== false ) {
				$terms = preg_split( '/[+]+/', $custom_tax_terms );
				foreach ( $terms as $term ) {
					$tax_query[] = array_merge( $tax_query_defaults, array(
						'terms' => array( $term )
					) );
				}
			} else {
				$tax_query[] = array_merge( $tax_query_defaults, array(
					'terms' => preg_split( '/[,]+/', $custom_tax_terms )
				) );
			}                        
		}
		
		return $tax_query;
	}

	/**
	 *
	 * @return array
	 */
	private function getWPQueryArgsSimple(){
		$args = array(
			'post_type' => $this->getAttr('post_type'),
			'post_status' => 'publish',
			'posts_per_page' => $this->getAttr('posts_per_page'),
			'post__not_in' => $this->getExcludedPosts(),
			'order' => $this->getAttr('posts_order'),
			'paged' => $this->getPageNumber(),
			'ignore_sticky_posts' => true
//			'ignore_sticky_posts' => $this->getAttr('show_sticky_posts') !== 'true'
		);
		
//		if ($this->getAttr('show_sticky_posts') !== 'true') {
//			$sticky_posts = get_option('sticky_posts', array());
//			$args['post__not_in'] = array_merge($args['post__not_in'], $sticky_posts);
//			$args['ignore_sticky_posts'] = true;
//		}
		$tax_query = $this->getWPQueryTaxonomyArgs();
		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}
		return $args;
	}

	/**
	 *
	 * @return array
	 */
	private function getWPQueryArgsIDs(){
		$args = array(
			'post_type' => 'any',
			'post__in' => array_diff(explode(',', $this->getAttr('ids')), $this->getExcludedPosts()),
			'orderby' => 'post__in',
			'post_status' => 'publish',
			'paged' => $this->getPageNumber(),
			'ignore_sticky_posts' => true
		);		
		return $args;
	}

	/**
	 *
	 * @return array
	 */
	private function getWPQueryArgsCustomQuery(){
		$custom_query = html_entity_decode(base64_decode($this->getAttr('custom_query')));

		wp_parse_str($custom_query, $args);		

		if (isset($args['post__not_in']) ) {
			if (is_array($args['post__not_in'])) {
				$args['post__not_in'] = array_unique(array_merge($args['post__not_in'], $this->getExcludedPosts()));
			}
		} else if (isset($args['post__in']) ){
			if (is_array($args['post__in'])) {
				$args['post__in'] = array_diff($args['post__in'], $this->getExcludedPosts());
			}
		} else {
			$args['post__not_in'] = $this->getExcludedPosts();
		}

		if (!isset($args['posts_per_page'])) {
			$wp_posts_per_page = get_option('posts_per_page');
			if ($wp_posts_per_page) {
				$args['posts_per_page'] = $wp_posts_per_page;
			}
		}

		if (isset($args['offset']) && $args['offset'] > 0) {
			// Setting the offset parameter overrides/ignores the paged parameter and breaks pagination. https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
			$args['offset'] += ( $this->getPageNumber() - 1 ) * $args['posts_per_page'];
			if (isset($args['paged'])) {
				unset($args['paged']);
			}
		} else {
			$args['paged'] = $this->getPageNumber();
		}

		return $args;
	}

	/**
	 *
	 * @return array Array of WP_Query arges based on shortcode attributes.
	 */
	private function getWPQueryArgs(){
        $args = array();
        switch ( $this->getAttr('query_type') ) {
            case self::QUERY_TYPE_SIMPLE:
				$args = $this->getWPQueryArgsSimple();                
                break;
            case self::QUERY_TYPE_CUSTOM:
				$args = $this->getWPQueryArgsCustomQuery();                
                break;
            case self::QUERY_TYPE_IDS:
                $args = $this->getWPQueryArgsIDs();         
                break;
        }
        $args['no_found_rows'] = false; // If true, forcibly turns off SQL_CALC_FOUND_ROWS even when limits are present.
		return $args;
	}
	
	/*
	 * Get array of posts ids that need to exclude for avoid self output.
	 * @return array
	 */
	private function getExcludedPosts(){
		// Get excluded posts
		$exclude_posts = array();
        if (MPCEShortcode::isContentEditor()) {
            if ( isset($_POST['postID']) && !empty($_POST['postID'])) {
                $exclude_posts[] = (int) $_POST['postID'];
            }
            $editedPost = get_post_meta($this->getCurrentPostId(), 'motopress-ce-edited-post', true);
            if (!empty($editedPost)) {
                $exclude_posts[] = (int) $editedPost;
            }
            if (isset($_GET['p'])){
                $exclude_posts[] = (int) $_GET['p'];
            }
        } else {
			$exclude_posts = array( $this->getCurrentPostId() );
        }
		return $exclude_posts;
	}
	
	private function enqueueScripts(){
		if (!MPCEShortcode::isContentEditor()) {			
			wp_enqueue_style('mpce-bootstrap-grid');
			wp_enqueue_script('mp-posts-grid');			
		}
	}

	/**
	 *
	 * @param bool|int $id Current post id. If FALSE detect id automatically (not usefull when ajax). Default FALSE.
	 */
	public function initPostID($id = false){
		if ($id === false) {
			// Detect current post id
			if (MPCEShortcode::isContentEditor()) {
				if ( isset($_POST['postID']) && !empty($_POST['postID'])) {
					$this->currentPostId = $_POST['postID'];
				} else {
					$this->currentPostId = get_the_ID();
				}
			} else {
				$this->currentPostId = get_the_ID();
			}
		} else {
			$this->currentPostId = $id;
		}
	}

	/**
	 *	Return post item html. Must be within WP_Query loop.
	 * @return string
	 */
	public function renderItem(){		
		$itemGridClass = 'motopress-filter-col mp-span' . ( 12 / $this->getAttr('columns') );
		$item_html = '<div class="' . $itemGridClass . '">';

		ob_start();		
		$post_type = get_post_type();
		$show_featured_image = $this->getAttr('show_featured_image');
		$image_size = $this->getAttr('image_size');
		$image_custom_size = $this->getAttr('image_custom_size');
		$featured_image_size = ($image_size === 'custom') ? array_pad(explode('x', $image_custom_size), 2, 0) : $image_size;
		$title_tag = $this->getAttr('title_tag');
		$show_date_comments = $this->getAttr('show_date_comments');
		$show_content = $this->getAttr('show_content');
		$short_content_length = $this->getAttr('short_content_length');
		$read_more_text = $this->getAttr('read_more_text');
		$template = $this->getAttr('template');
		$fullTemplatePath = $this->getFullTemplatePath($template);

		require($fullTemplatePath);
		
		$item_html .= ob_get_contents();
		ob_end_clean();
		
		$item_html .= '</div>';
		return $item_html;
	}

	/**
	 * Fix template relative path.
	 * Now Lite version dirname is 'motopress-content-editor-lite', but template path from older version contain dirname 'motopress-content-editor'.
	 * Second case when user update from lite to pro version.
	 *
	 * @param string $template
	 * @return string
	 */
	public static function fixTemplatePath($template){
		$pattern = '/(^plugins\/motopress-content-editor\/)/';
		$replacement = 'plugins/motopress-content-editor-lite/';
		return preg_replace($pattern, $replacement, $template);
	}

	/**
	 * Get full path to template.
	 * Fix hardcoded WP_PLUGIN_DIR in native MPCE templates
	 *
	 * @global array $motopressCESettings
	 * @param string $template Part of path of template relative to WP_CONTENT_DIR.
	 * @return string
	 */	
	public static function getFullTemplatePath($template) {
		global $motopressCESettings;
		$nativeTemplatePrefix = 'plugins/motopress-content-editor-lite/';
		$isMCPETemplate = strpos($template, $nativeTemplatePrefix) === 0;
		if ($isMCPETemplate) {
			$pattern = '/^' . str_replace('/', '\/', $nativeTemplatePrefix) . '/';
			$fullTemplatePath = preg_replace($pattern, $motopressCESettings['plugin_dir_path'], $template);
		} else {
			$fullTemplatePath = trailingslashit(WP_CONTENT_DIR) . $template;
		}
		return $fullTemplatePath;
	}

	/*
	 * Retrieve the current shortcode's attributes.
	 * @return array
	 */
	private function getShortcodeAttrs(){
		return $this->shortcodeAttrs;
	}

	/**
	 * Generate signed shortcode attrs string.
	 * @param array $shortcodeAttrs
	 * @return string
	 */
	public static function generateShortcodeAttrsString($shortcodeAttrs){
		$secretKey = self::getSecretKey();
		$shortcodeAttrsJSON = json_encode($shortcodeAttrs);
		$publicKey = md5($shortcodeAttrsJSON . $secretKey);
		$shortcodeAttrs['key'] = $publicKey;		
		return http_build_query($shortcodeAttrs);
	}

	/**
	 *	Retrieve shortcode attrs from string.
	 * @param type $shortcodeAttrsStr
	 * @return type
	 */
	public static function parseShortcodeAttrsFromStr($shortcodeAttrsStr){
		parse_str($shortcodeAttrsStr, $shortcodeAttrs);
		unset($shortcodeAttrs['key']);
		return $shortcodeAttrs;
	}

	/**
	 * Check is valid signed shortcode attributes string.
	 *
	 * @param string $shortcodeAttrsStr
	 * @return bool
	 */
	public static function isValidShortcodeAttrsStr($shortcodeAttrsStr){
		$shortcodeAttrs = self::parseShortcodeAttrsFromStr($shortcodeAttrsStr);
		$testShortcodeAttrStr = self::generateShortcodeAttrsString($shortcodeAttrs);
		return $testShortcodeAttrStr === $shortcodeAttrsStr;
	}

	/**
	 * Render shortcode.
	 * @return string
	 */
	public function render(){
		$this->enqueueScripts();
		$result = '';
		
		$mp_style_classes = $this->getAttr('mp_style_classes');
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;

		$shortcodeAttrStr = self::generateShortcodeAttrsString($this->getShortcodeAttrs());		
        $result .= '<div id="' . $this->getUniqueId() . '" class="motopress-posts-grid-obj motopress-posts-grid-gap-'. $this->getAttr('posts_gap')
			. MPCEShortcode::handleCustomStyles($this->getAttr('mp_custom_style'), MPCEShortcode::PREFIX . 'posts_grid')
			. MPCEShortcode::getMarginClasses($this->getAttr('$margin')) . MPCEShortcode::getBasicClasses(MPCEShortcode::PREFIX . 'posts_grid', true)
			. $mp_style_classes . '" data-shortcode-attrs="' . $shortcodeAttrStr . '" data-post-id="' . $this->getCurrentPostId() . '">';

		if ($this->isFiltersEnabled()) {
			$result .= $this->outputFilters();
		}

		$result .= $this->renderItems();

		if ($this->isPaginationEnabled()) {
			$result .= $this->outputPagination();
		} else if ($this->isLoadMoreEnabled()) {
			$result .= $this->outputLoadMore();
		}
		
        $result .= '</div>';

		return $result;
	}

	/**
	 * Render posts grid's items. Using WP_Query loop.
	 * @return string
	 */
	public function renderItems(){
		$custom_query = $this->getQuery();
		$items_html = '';
		$rowClass = 'mp-row-fluid motopress-filter-row';
		
        if( $custom_query->have_posts() ) {
			$itemsColumns = $this->getAttr('columns');
			$i = 0;
			$items_html .= '<div class="motopress-paged-content" data-columns="' . $itemsColumns . '">';
			$items_html .= '<div class="' . $rowClass . '">';
			
			while ($custom_query->have_posts()) {
				$custom_query->the_post();
				MPCEShortcode::setCurPostData(null, get_the_ID());				
				$items_html .= $this->renderItem();
				$isRowFull = ($i % $itemsColumns == $itemsColumns - 1) && ($i != $custom_query->post_count - 1);
				if ( $isRowFull) {
					$items_html .= '</div>';
					$items_html .= '<div class="' . $rowClass . '">';
				}
				$i++;
			} // while have posts

			$items_html .= '</div>';
			$items_html .= '</div>';
            
        } else {
            $items_html .=  '<p>' . __('No posts found.') . '</p>';
        }
		
		wp_reset_postdata();
		return $items_html;
	}

	/**
	 * Retrieve array of posts grid's items. Using WP_Query loop.
	 * @return array
	 */
	public function getItemsArray(){
		$itemsArray = array();
		$custom_query = $this->getQuery();
		while ($custom_query->have_posts()) {
			$custom_query->the_post();
			MPCEShortcode::setCurPostData(null, get_the_ID());
			$itemsArray[] = $this->renderItem();
		}
		wp_reset_postdata();
		return $itemsArray;
	}
	
	private function isFiltersEnabled(){
		// Filters enabled only for simple query
		return $this->getAttr('query_type') === self::QUERY_TYPE_SIMPLE && $this->getAttr('filter') !== self::FILTER_NONE_TAXONOMY;
	}

	private function isPaginationEnabled(){
		return $this->getAttr('pagination') === 'true';
	}

	private function isLoadMoreEnabled(){
		return $this->getAttr('load_more_btn') === 'true';
	}

	private function hasPrevPage(){
		return $this->getPageNumber() > 1;
	}

	private function hasNextPage(){
		return $this->getPageNumber() < $this->getQuery()->max_num_pages;
	}

	private function outputPagination(){
		$posts_order = $this->getAttr('posts_order');
		if ($posts_order === 'ASC') {
			$nextPageLabel = __('Newer posts');
			$prevPageLabel = __('Older posts');
		} else {
			$nextPageLabel = __('Older posts');
			$prevPageLabel = __('Newer posts');
		}
		$pagination_html = '<div class="mp-row-fluid motopress-posts-grid-pagination">';		
		if ($this->hasPrevPage()) {
			$prevPageNumber = $this->getPageNumber() - 1;
			$pagination_html .= '<div class="nav-prev"><a href="#" data-page="' . $prevPageNumber . '"><span class="meta-nav">&#8592;</span>' . $prevPageLabel . '</a></div>';
		}
		if ($this->hasNextPage()) {
			$nextPageNumber = $this->getPageNumber() + 1;
			$pagination_html .= '<div class="nav-next"><a href="#" data-page="' . $nextPageNumber . '">' . $nextPageLabel . '<span class="meta-nav">&#8594;</span></a></div>';
		}
		$pagination_html .= '</div>';
		return $pagination_html;
	}

	private function outputLoadMore(){
		$load_more_html = '';
		if ($this->hasNextPage()) {
			$load_more_html .= '<div class="motopress-load-more-obj">';
			$load_more_html .= '<a href="#" class="motopress-load-more" data-page="' . ($this->getPageNumber() + 1) . '">' . $this->getAttr('load_more_text') . '</a>';
			$load_more_html .= '</div>';
		}
		return $load_more_html;
	}

	private function outputFilters(){
		$filterInnerHtml = '';		
		$filter_btn_color = $this->getAttr('filter_btn_color');
		$filterBtnClass = ( $filter_btn_color !== 'none') ? $filter_btn_color . ' motopress-btn motopress-btn-size-small motopress-btn-rounded' : '';
		$filter_btn_divider = $this->getAttr('filter_btn_divider');		
		// Button template:
		//	 %1$s - divider
		//   %2$s - filter
		//   %3$s - active class
		//   %4$s - button text
		$button_template = '<li><div class="' . ($filter_btn_color !== 'none' ? 'motopress-filter-button-wrapper motopress-button-obj' : 'motopress-link') . '">'
				. '%s'
				. '<a href="#" data-filter="%s" class="motopress-filter-btn ' . $filterBtnClass . ' %s">'
				. '%s'
				. '</a>'
				. '</div></li>';
		
		$filter = $this->getAttr('filter');
		if ($filter === self::FILTER_FIRST_TAXONOMY || $filter === self::FILTER_BOTH_TAXONOMY) {
			$filter_tax_1 = $this->getAttr('filter_tax_1');
			$firstTaxonomy = !empty($filter_tax_1) ? $filter_tax_1 : 'category';
			$filterInnerHtml .= $this->getTaxonomyFilterButtons($firstTaxonomy, $button_template, 'first');
		}

		if ($filter === self::FILTER_SECOND_TAXONOMY || $filter === self::FILTER_BOTH_TAXONOMY) {
			$filter_tax_2 = $this->getAttr('filter_tax_2');
			$secondTaxonomy = !empty($filter_tax_2) ? $filter_tax_2 : 'post_tag';
			$filterInnerHtml .= $this->getTaxonomyFilterButtons($secondTaxonomy, $button_template, 'second');
		}

		$filter_html = '<div class="motopress-filter">';
			$filter_html .= $filterInnerHtml;
		$filter_html .= '</div>';
		return $filter_html;
	}

	/**
	 *
	 * @param string $taxName 
	 * @param type $button_template
	 * @param type $taxType
	 * @return string
	 */
	function getTaxonomyFilterButtons($taxName, $button_template, $taxType){
		$filterInnerHtml = '';
		$active_class = 'ui-state-active';
		$currentPostTypeTaxonomies = get_object_taxonomies( $this->getAttr('post_type'), 'objects' );
		$dividerHtml = ($this->getAttr('filter_btn_color') === 'none' && $this->getAttr('filter_btn_divider') !== '') 
			? '<span class="motopress-posts-grid-filter-divider">' . esc_html( $this->getAttr('filter_btn_divider') ). '</span>'
			: '';
		
		if (array_key_exists($taxName, $currentPostTypeTaxonomies)) {
			$taxTerms = get_terms($taxName);			
			if (!empty($taxTerms)) {
				$filterInnerHtml .= '<div class="motopress-filter-group-wrapper">';
				$defaultFilterTitle = $taxType === 'first' ? __('Categories') : __('Tags');
				$customFilterTitle = $taxType === 'first' ? $this->getAttr('filter_cats_text') : $this->getAttr('filter_tags_text');
				$filterTitle = $customFilterTitle !== '' ? $customFilterTitle : $defaultFilterTitle;
				$filterInnerHtml .= '<span class="motopress-filter-label">' . $filterTitle . '&nbsp;</span>';

				$filterInnerHtml .= '<ul class="motopress-filter-group" data-group="' . $taxName . '">';
				
				$defaultFilterAllText = $taxType === 'first' ? __('All Categories') : __('All Tags');
				$filterAllCustomText = $this->getAttr('filter_all_text');
				$filterAllText = $filterAllCustomText !== '' ? $filterAllCustomText : $defaultFilterAllText;
				
				// Add "View All" button
				$filterInnerHtml .= sprintf($button_template, '', '', $active_class, $filterAllText);
				foreach ($taxTerms as $term) {
					$filterInnerHtml .= sprintf($button_template, $dividerHtml, $term->slug, '', $term->name);
				}
				$filterInnerHtml .= '</ul></div>';
			}
		}
		
		return $filterInnerHtml;
	}

	/**
	 * AJAX-callback for frontend filtering.
	 *
	 * @global array $motopressCESettings
	 */
	public static function ajaxFilter() {
		self::runPostsGrid();
		global $motopressCESettings;
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/verifyNonce.php';
		$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
		if (isset($_POST['shortcode_attrs'])
			&& !empty($_POST['shortcode_attrs'])
			&& self::isValidShortcodeAttrsStr($_POST['shortcode_attrs'])
			&& $post_id
		){
			$shortcodeAttrs = self::parseShortcodeAttrsFromStr($_POST['shortcode_attrs']);
			$filters = isset($_POST['filters']) && is_array($_POST['filters']) ? $_POST['filters'] : array();
			$postsGrid = new MPCEShortcodePostsGrid($shortcodeAttrs, array('filters' => $filters, 'post_id' => $post_id));
			$response = array(
				'items' => $postsGrid->renderItems(),
				'load_more' => $postsGrid->isLoadMoreEnabled() ? $postsGrid->outputLoadMore() : '',
				'pagination' => $postsGrid->isPaginationEnabled() ? $postsGrid->outputPagination() : ''
			);
			$response = self::addCustomStyleToAJAXResponse($response);
			self::stopPostsGrid();
			wp_send_json_success($response);
		} else {
			self::stopPostsGrid();
			wp_send_json_error();
		}
    }

	/**
	 * AJAX-callback for loading more posts.
	 *
	 * @global array $motopressCESettings
	 */
	public static function ajaxLoadMore(){
		self::runPostsGrid();
		global $motopressCESettings;
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/verifyNonce.php';
		$page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT);
		$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
		if (isset($_POST['shortcode_attrs']) && !empty($_POST['shortcode_attrs']) 
			&& self::isValidShortcodeAttrsStr($_POST['shortcode_attrs'])
			&& $page
			&& $post_id
		){
			$shortcodeAttrs = self::parseShortcodeAttrsFromStr($_POST['shortcode_attrs']);
			$filters = isset($_POST['filters']) && is_array($_POST['filters']) ? $_POST['filters'] : false;			
			$postsGrid = new MPCEShortcodePostsGrid($shortcodeAttrs, array('filters' => $filters, 'page' => $page, 'post_id' => $post_id));		
			$response = array(
				'items' => $postsGrid->getItemsArray(),
				'load_more' => $postsGrid->outputLoadMore()
			);
			$response = self::addCustomStyleToAJAXResponse($response);
			self::stopPostsGrid();
			wp_send_json_success($response);
		} else {
			self::stopPostsGrid();
			wp_send_json_error();
		}
	}

	/**
	 * AJAX-callback for pagination.
	 *
	 * @global array $motopressCESettings
	 */
	public static function ajaxTurnPage(){
		self::runPostsGrid();
		global $motopressCESettings;		
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/verifyNonce.php';
		if (isset($_POST['shortcode_attrs']) && !empty($_POST['shortcode_attrs']) 
			&& self::isValidShortcodeAttrsStr($_POST['shortcode_attrs'])			
			&& ( $page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT) )
			&& ( $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT) )
		){
			$shortcodeAttrs = self::parseShortcodeAttrsFromStr($_POST['shortcode_attrs']);
			$filters = isset($_POST['filters']) && is_array($_POST['filters']) ? $_POST['filters'] : false;
			$postsGrid = new MPCEShortcodePostsGrid($shortcodeAttrs, array('filters' => $filters, 'page' => $page, 'post_id' => $post_id));		
			$response = array(
				'items' => $postsGrid->renderItems(),
				'pagination' => $postsGrid->outputPagination()				
			);
			$response = self::addCustomStyleToAJAXResponse($response);
			self::stopPostsGrid();
			wp_send_json_success($response);
		} else {
			self::stopPostsGrid();
			wp_send_json_error();
		}
	}

	public static function addCustomStyleToAJAXResponse($response){
		$mpceCustomStyleManager = MPCECustomStyleManager::getInstance();
		$response['custom_styles'] = array(
			'private' => $mpceCustomStyleManager->getPrivateStylesArr()
		);

		if (!filter_input(INPUT_POST, 'page_has_presets', FILTER_VALIDATE_BOOLEAN) && $mpceCustomStyleManager->isEnqueuedPresetsStyles()) {
			$response['custom_styles']['presets'] = MPCECustomStyleManager::getPresetsStylesTag();
		}		
		return $response;
	}

	public static function runPostsGrid(){
		self::$running = true;
	}

	public static function stopPostsGrid(){
		self::$running = false;
	}

	public static function isRunning(){
		return self::$running;
	}
}