<?php
/**
 * @note Preset - stored sitewide style.
 * @note Private - postwide object-unique style.
 * 
 */
class MPCECustomStyleManager {

	const WP_OPTION_PRESETS_LAST_ID = 'motopress-ce-preset-styles-last-id';
	const WP_OPTION_PRESETS = 'motopress-ce-preset-styles';
	const WP_OPTION_PRESETS_CSS = 'motopress-ce-preset-styles-css';
	const WP_META_BOX_STYLES_NONCE = 'motopress-ce-preset-styles-nonce';

	const WP_POST_META_STYLES = '_motopress-ce-private-styles';
	
	const PRIVATE_STYLE_PREFIX = 'mpce-prvt-';
	const PRESET_PREFIX = 'mpce-prst-';
	const RULE_DISABLE_PREFIX = 'mpce-dsbl-';
	const PRESET_DEFAULT_LABEL = 'Preset';
	
	private $enqueuedPrivateStyles = array();
	private $isEnqueuedPresetsStyles = false;
	private static $instance = null;

	public static function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new MPCECustomStyleManager();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->addActions();
	}

	private function addActions(){
		add_action('wp_footer', array($this, 'printStyles'), 21);				
	}

	public function enqueuePrivateStyle($postId){
		if (!isset($this->enqueuedPrivateStyles[$postId])) {
			$privateStyles = self::getAllPrivates($postId);
			if (is_array($privateStyles)) {
				$this->enqueuedPrivateStyles[$postId] = array();
				foreach ($privateStyles as $name => $details) {
					if (!isset($this->enqueuedPrivateStyles[$postId][$name])) {
						$this->enqueuedPrivateStyles[$postId][$name] = $details['css'];
					}
				}
			}
		}
	}

	public function enqueuePresetsStyle(){
		$this->isEnqueuedPresetsStyles = true;
	}

	public function getEnqueuedtPrivateStyles(){
		return $this->enqueuedPrivateStyles;
	}
	
	public function isEnqueuedPresetsStyles(){
		return $this->isEnqueuedPresetsStyles;
	}

	/**
	 *
	 * @return array
	 */
	public function getPrivateStylesArr(){
		$privateStyles = array();
		foreach ($this->enqueuedPrivateStyles as $page => $styles) {
			$privateStyles = array_merge($privateStyles, $styles);
		}
		return $privateStyles;
	}

	/**
	 *
	 * @return string
	 */
	public function getEnqueuedPrivateStylesPostIds(){
		return join(',', array_keys($this->enqueuedPrivateStyles));
	}

	/**
	 *
	 * @param bool $separateTags Whether put each private style in separate tag.
	 * @return string
	 */
	public function getPrivateStylesTag($separateTags = false){
		$tags = '';
		if ($separateTags) {
			foreach ($this->getPrivateStylesArr() as $className => $css) {
				$tags .= '<style id="' . esc_attr($className) . '" type="text/css">' . wp_strip_all_tags($css) . '</style>';
			}			
		} else {
			$tags .= '<style id="motopress-ce-private-styles" data-posts="' . esc_attr($this->getEnqueuedPrivateStylesPostIds()) . '" type="text/css">'
				. wp_strip_all_tags( implode($this->getPrivateStylesArr()) ) . '</style>';
		}
		return $tags;
	}

	public function printPrivateStyles(){
		if (MPCEShortcode::isContentEditor()) {
			echo '<div id="motopress-ce-private-styles-wrapper">';			
			echo $this->getPrivateStylesTag(true);
			echo '</div>';
		} else {			
			echo $this->getPrivateStylesTag();
		}
	}

	public function printStyles() {
		$this->printPresetsStyles();
		$this->printPrivateStyles();
	}

	public function printPresetsStyles() {
		if (MPCEShortcode::isContentEditor()) {
			$presets = self::getAllPresets();
			echo '<div id="motopress-ce-preset-styles-wrapper">';
			foreach($presets as $presetName => $presetDetails) {
				echo '<style id="' . esc_attr($presetName) . '" type="text/css">' . wp_strip_all_tags($presetDetails['css']) . '</style>';
			}
			echo '</div>';
		} else if ($this->isEnqueuedPresetsStyles()) {
			echo self::getPresetsStylesTag();
		}
	}

	public static function hasPrivateClass($classes){				
		return preg_match('/(?:^|\s)+' . self::PRIVATE_STYLE_PREFIX . '[\d]+-[\S]+(?:$|\s)+/', $classes) === 1;
	}

	public static function hasPresetClass($classes){
		return preg_match('/(?:^|\s)+' . self::PRESET_PREFIX . '[\d]+(?:$|\s)+/', $classes) === 1;
	}

	public static function getAllPrivates($postId = null) {
		if (!isset($postId)) $postId = get_the_ID();

		if (is_preview()) {
			$styles = get_transient(self::getTransientName(self::WP_POST_META_STYLES, $postId));
			if ($styles === false) {
				$styles = get_post_meta($postId, self::WP_POST_META_STYLES, true);
			} else {
				$styles = stripslashes($styles);
			}
		} else {
			$styles = get_post_meta($postId, self::WP_POST_META_STYLES, true);
		}

		if (!empty($styles) && ($styles = json_decode($styles, true)) !== NULL) {
			return $styles;
		} else {
			return array();
		}
	}
	
	/**
	 * @param $postId
	 * @param $styles - JSON (if $isPreview is TRUE then $styles must be slashes, else it does not matter)
	 * @param bool|false $isPreview
	 */
	public static function savePrivates($postId, $styles, $isPreview = false) {
		if ($isPreview) {
			set_transient(self::getTransientName(self::WP_POST_META_STYLES, $postId), $styles, DAY_IN_SECONDS);
		} else {
			update_post_meta($postId, self::WP_POST_META_STYLES, $styles);
		}
	}

	public static function getAllPresets(){
		return get_option(self::WP_OPTION_PRESETS, array());
	}

	public static function getPresetsLastId(){
		return (int) get_option(self::WP_OPTION_PRESETS_LAST_ID, 0);
	}
	
	public static function getPresetsStylesTag() {
		$presetStyles = self::getPresetsStyles();
		return '<style id="motopress-ce-presets-styles" type="text/css">' . wp_strip_all_tags( $presetStyles ) . '</style>';
	}	

	/**
	 *
	 * @param (array|null) $presets
	 * @return string
	 */
	public static function getPresetsStyles($presets = null) {
		$str = '';
		if (!isset($presets)) {
			if (is_preview()) {
				$str = get_transient(self::getTransientName(self::WP_OPTION_PRESETS_CSS, get_the_ID()));
				if ($str === false) $str = get_option(self::WP_OPTION_PRESETS_CSS, '');
			} else {
				$str = get_option(self::WP_OPTION_PRESETS_CSS, '');
			}
		} else if (is_array($presets)) {
			foreach ($presets as $name => $details) {
				$str .= $details['css'];
			}
		}
		return $str;
	}

	public static function getLocalizeJSData(){
		return array(
			'const' => array(
				'wpPostMetaPresetStyles' => self::WP_OPTION_PRESETS,
				'wpPostMetaPrivateStyle' => self::WP_POST_META_STYLES,
				'prefixPrivateClass' => self::PRIVATE_STYLE_PREFIX,
				'prefixPresetClass' => self::PRESET_PREFIX,
				'prefixRuleDisable' => self::RULE_DISABLE_PREFIX,
				'presetDefaultLabel' => self::PRESET_DEFAULT_LABEL
			),
			'private' => self::getAllPrivates(),
			'presets' => self::getAllPresets(),
			'presets_last_id' => self::getPresetsLastId()
		);
	}

	public static function compareLabel($a, $b) {
		return strnatcasecmp($a['label'], $b['label']);
	}

	/**
	 * @param $presets
	 * @param bool|false $isPreview
	 * @param bool|false $postId - Must be set if $isPreview is true
	 * @return array
	 */
	public static function savePresets($presets, $isPreview = false, $postId = null) {
		if (!is_array($presets)) $presets = array();

		uasort($presets, array('MPCECustomStyleManager', 'compareLabel'));
		$presetsCSS = self::getPresetsStyles($presets);

		if ($isPreview) {
			set_transient(self::getTransientName(self::WP_OPTION_PRESETS, $postId), $presets, DAY_IN_SECONDS);
			set_transient(self::getTransientName(self::WP_OPTION_PRESETS_CSS, $postId), $presetsCSS, DAY_IN_SECONDS);
		} else {
			update_option(self::WP_OPTION_PRESETS, $presets);
			update_option(self::WP_OPTION_PRESETS_CSS, $presetsCSS);
		}
		
		return array(
			'presets' => $presets,
			'css' => $presetsCSS
		);
	}


	/**
     * @param string $shortcodeName
     * @param bool $addSpace
     * @return string
     */
	public static function getLimitationClass($shortcodeName, $addSpace = false){		
		$motopressCELibrary = MPCELibrary::getInstance();
		$result = '';
		if (isset($motopressCELibrary) && !empty($shortcodeName)) {
            $object = &$motopressCELibrary->getObject($shortcodeName);			
            if ($object) {
                $customStyle = &$object->getStyle('mp_custom_style');			
                if ($customStyle && isset($customStyle['limitation']) && !empty($customStyle['limitation'])) {
                    $classes = array();					
					foreach ($customStyle['limitation'] as $limitationRule) {
						$classes[] = self::RULE_DISABLE_PREFIX . $limitationRule;
					}					
                    if (!empty($classes)) $result = implode(' ', $classes);
                    if (!empty($result) && $addSpace) $result = ' ' . $result;
                }
            }
        }
		return $result;
	}

	public static function optionsImportWhitelistFilter($options){
		$options[] = self::WP_OPTION_PRESETS_LAST_ID;
		$options[] = self::WP_OPTION_PRESETS;
		$options[] = self::WP_OPTION_PRESETS_CSS;
		return $options;
	}

	private static function getTransientName($name, $postId) {
		return trim($name, '_') . '-' . $postId;
	}

	/* MetaBox */
	public static function stylesMetaBoxAdd($postType) {
		global $motopressCESettings;
	    require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/Access.php';

		$ceAccess = new MPCEAccess();
	    $postTypes = get_option('motopress-ce-options', array('post', 'page'));

		if (in_array($postType, $postTypes) && post_type_supports($postType, 'editor') && $ceAccess->hasAccess()) {
			global $motopressCELang;
			add_meta_box(
				'motopress-ce-styles',
				strtr($motopressCELang->CEStylesMetaBoxTitle, array('%BrandName%' => $motopressCESettings['brand_name'])),
				array('MPCECustomStyleManager', 'stylesMetaBoxPrint'),
				null, 'normal', 'low'
			);
		}
	}

	public static function stylesMetaBoxPrint($post) {
		wp_nonce_field(self::WP_META_BOX_STYLES_NONCE, self::WP_META_BOX_STYLES_NONCE);
		echo '<textarea name="' . self::WP_OPTION_PRESETS . '"></textarea>';
		echo '<textarea name="' . self::WP_POST_META_STYLES . '">' . json_encode(self::getAllPrivates($post->ID), JSON_FORCE_OBJECT) . '</textarea>';
	}

	public static function stylesMetaBoxSave($postId) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return; // Don't save on auto save
		if (!isset($_POST[self::WP_META_BOX_STYLES_NONCE]) || !wp_verify_nonce($_POST[self::WP_META_BOX_STYLES_NONCE], self::WP_META_BOX_STYLES_NONCE)) return;

		$isPreview = isset($_POST['wp-preview']) && $_POST['wp-preview'] === 'dopreview';
		if ($isPreview) $postId = get_the_ID();
		self::presetsMetaBoxSave($postId, $isPreview);
		self::privatesMetaBoxSave($postId, $isPreview);
	}

	private static function presetsMetaBoxSave($postId, $isPreview = false) {
		if ($isPreview) {
			$saved = false;
			if (isset($_POST[self::WP_OPTION_PRESETS])) {
				// not empty and valid
				if (($presets = json_decode(stripslashes($_POST[self::WP_OPTION_PRESETS]), true)) !== NULL) {
					self::savePresets($presets, $isPreview, $postId);
					$saved = true;
				}
			}
			if (!$saved) {
				$presets = get_option(self::WP_OPTION_PRESETS, '');
				self::savePresets($presets, $isPreview, $postId);
			}

		} elseif (isset($_POST[self::WP_OPTION_PRESETS]) && !wp_is_post_autosave($postId) && !wp_is_post_revision($postId)) {
			// not empty and valid
			if (($presets = json_decode(stripslashes($_POST[self::WP_OPTION_PRESETS]), true)) !== NULL) {
				self::savePresets($presets);
			}
		}
	}

	private static function privatesMetaBoxSave($postId, $isPreview = false) {
		if ($isPreview) {
			$styles = '';
			if (isset($_POST[self::WP_POST_META_STYLES])) $styles = $_POST[self::WP_POST_META_STYLES];
			if (!$styles) $styles = addslashes(get_post_meta($postId, self::WP_POST_META_STYLES, true));
			self::savePrivates($postId, $styles, $isPreview);

		} elseif (isset($_POST[self::WP_POST_META_STYLES]) && $_POST[self::WP_POST_META_STYLES] && !wp_is_post_autosave($postId) && !wp_is_post_revision($postId)) {
			self::savePrivates($postId, $_POST[self::WP_POST_META_STYLES]);
		}
	}
	/* End MetaBox */

}

add_filter('options_import_whitelist', array('MPCECustomStyleManager', 'optionsImportWhitelistFilter'));
add_action('add_meta_boxes', array('MPCECustomStyleManager', 'stylesMetaBoxAdd'));
add_action('save_post', array('MPCECustomStyleManager', 'stylesMetaBoxSave'));