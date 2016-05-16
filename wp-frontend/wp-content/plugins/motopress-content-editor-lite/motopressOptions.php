<?php
function motopressCEOptions() {
	global $motopressCELang, $motopressCESettings;

    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error(
            'motopressSettings',
            esc_attr('settings_updated'),
            $motopressCELang->CEOptMsgUpdated,
            'updated'
        );
    }

	$pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : $motopressCESettings['plugin_short_name'];

	echo '<div class="wrap">';
	echo '<h1>' . $motopressCELang->motopressOptions . '</h1>';

	// Tabs
	$tabs = apply_filters('admin_mpce_settings_tabs', array(
		$motopressCESettings['plugin_short_name'] => array(
			'label' => $motopressCELang->CE,
			'priority' => 0,
			'callback' => 'motopressCESettingsTabContent'
		)
	));

    echo '<h2 class="nav-tab-wrapper">';
	if (is_array($tabs)) {
		uasort($tabs, 'motopressCESortTabs');
		foreach ($tabs as $tabId => $tab) {
			$class = ($tabId == $pluginId) ? ' nav-tab-active' : '';
			echo '<a href="' . esc_url(add_query_arg(array('page' => $_GET['page'], 'plugin' => $tabId), admin_url('admin.php'))) . '" class="nav-tab' . $class . '">' . esc_html($tab['label']) . '</a>';
		}
	}
    echo '</h2>';

	if (is_array($tabs) && array_key_exists($pluginId, $tabs)) {
		$callbackFunc = $tabs[$pluginId]['callback'];
		if (!empty($callbackFunc)) {
			if (
				(is_string($callbackFunc) && function_exists($callbackFunc)) ||
				(is_array($callbackFunc) && count($callbackFunc) === 2 && method_exists($callbackFunc[0], $callbackFunc[1]))
			) {
				call_user_func($callbackFunc);
			}
		}
	}
	echo '</div>';
}

function motopressCESettingsTabContent() {
	global $motopressCELang;
	settings_errors('motopressSettings', false);
	echo '<form actoin="options.php" method="POST">';
//    settings_fields('motopressOptionsFields');
	do_settings_sections('motopress_options');
	echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="' . $motopressCELang->CESaveBtnText . '" /></p>';
	echo '</form>';
}

add_action('admin_init', 'motopressCEInitOptions');
function motopressCEInitOptions() {
    global $motopressCELang;
    global $wp_version;

    register_setting('motopressLanguageOptionsFields', 'motopressLanguageOptions');
    add_settings_section('motopressLanguageOptionsFields', '', 'motopressCELanguageOptionsSecTxt', 'motopress_options');
    add_settings_field('motopressLanguageOptions', $motopressCELang->language, 'motopressCELanguageSettings', 'motopress_options', 'motopressLanguageOptionsFields');

//    register_setting('motopressCEOptionsFields', 'motopressCEOptions'/*, 'plugin_options_validate'*/);
    register_setting('motopressCEOptionsFields', 'motopressContentEditorOptions'/*, 'plugin_options_validate'*/);
    add_settings_section('motopressCEOptionsFields', '', 'motopressCEOptionsSecTxt', 'motopress_options');
    add_settings_field('motopressContentType', $motopressCELang->CEOptContentTypes, 'motopressCEContentTypeSettings', 'motopress_options', 'motopressCEOptionsFields');

    $currentUser = wp_get_current_user();
    if (in_array('administrator', $currentUser->roles)) {
        register_setting('motopressCERolesSettingsFields', 'motopressCERolesOptions');
        add_settings_section('motopressCERolesSettingsFields', '', 'motopressCERolesSettingsSecTxt', 'motopress_options');
        add_settings_field('motopressRoles', $motopressCELang->CEOptRolesSettings, 'motopressCERolesSettingsFields', 'motopress_options', 'motopressCERolesSettingsFields');
    }

    register_setting('motopressCESpellcheckSettingsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCESpellcheckSettingsFields', '', 'motopressCESpellcheckSecTxt', 'motopress_options');
    add_settings_field('motopressSpellcheck', $motopressCELang->CEOptSpellcheckSettings, 'motopressCESpellcheckFields', 'motopress_options', 'motopressCESpellcheckSettingsFields');

	register_setting('motopressCEFixedRowWidthOptionsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEFixedRowWidthOptionsFields', '', 'motopressCEFixedRowWidthSecTxt', 'motopress_options');
    add_settings_field('motopressCEFixedRowWidth', $motopressCELang->CEOptFixedRowWidth, 'motopressCEFixedRowWidthFields', 'motopress_options', 'motopressCEFixedRowWidthOptionsFields');

    register_setting('motopressCECustomCSSOptionsFields', 'motopressContentEditorOptions'/*, 'plugin_options_validate'*/);
    add_settings_section('motopressCECustomCSSOptionsFields', '', 'motopressCECustomCSSSecTxt', 'motopress_options');
    add_settings_field('motopressCustomCSS', $motopressCELang->CEOptCustomCSS, 'motopressCECustomCSSFields', 'motopress_options', 'motopressCECustomCSSOptionsFields');

    register_setting('motopressCEExcerptSettingsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEExcerptSettingsFields', '', 'motopressCEExcerptSecTxt', 'motopress_options');
    add_settings_field('motopressExcerpt', $motopressCELang->CEOptExcerptSettings, 'motopressCEExcerptFields', 'motopress_options', 'motopressCEExcerptSettingsFields');

    register_setting('motopressCEGoogleFontsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEGoogleFontsFields', '', 'motopressCEGoogleFontsSecTxt', 'motopress_options');
    add_settings_field('motopressGoogleFonts', $motopressCELang->CEOptGoogleFontsSettings, 'motopressCEGoogleFontsFields', 'motopress_options', 'motopressCEGoogleFontsFields');

    if (is_multisite() && is_main_site() && is_super_admin()) {
        register_setting('motopressCEHideSettingsFields', 'motopressContentEditorOptions');
        add_settings_section('motopressCEHideSettingsFields', '', 'motopressCEHideSecTxt', 'motopress_options');
        add_settings_field('motopressHide', $motopressCELang->CEOptHideOptionsSettings, 'motopressCEHideFields', 'motopress_options', 'motopressCEHideSettingsFields');
    }
}

function motopressCELanguageOptionsSecTxt() {}
function motopressCELanguageSettings() {
    global $motopressCESettings, $motopressCELang;

    $languageFileList = glob($motopressCESettings['plugin_dir_path'] . 'lang/*.json');
    $languageArray = array();
    foreach($languageFileList as $path) {
        $file = basename($path);
        $fileContents = file_get_contents($path);
        $fileContentsJSON = json_decode($fileContents);
        $languageName = $fileContentsJSON->{'name'};
        $languageArray[$file] = $languageName;
    }
    natsort($languageArray);
    echo '<select class="motopress-language" name="language" id="language">';
    foreach ($languageArray as $language => $languageName) {
        $selected = ($language === $motopressCESettings['lang']['mpce']) ? ' selected' : '';
        echo '<option value="'.$language.'"'.$selected.'>' . $languageName . '</option>';
    }
    echo '</select><br />';
    $isHideLinkTranslationService = apply_filters('mpce_hide_link_translation_service', false);
    if (!$isHideLinkTranslationService) {
        echo '<p class="description">' . strtr($motopressCELang->CEOptLanguageSettingsDescription, array('%link%' => $motopressCESettings['translation_service_url'], '%BrandName%' => $motopressCESettings['brand_name'])) . '</p>';
    }
}

function motopressCEOptionsSecTxt() {}
function motopressCEContentTypeSettings() {
    global $motopressCELang, $motopressCESettings;
    $postTypes = get_post_types(array('public' => true));
    $excludePostTypes = array('attachment' => 'attachment');
    $postTypes = array_diff_assoc($postTypes, $excludePostTypes);
    $checkedPostTypes = get_option('motopress-ce-options', array('post', 'page'));

    foreach ($postTypes as $key => $val) {
        if (post_type_supports($key, 'editor')) {
            $checked = '';
            if (in_array($key, $checkedPostTypes)) {
                $checked = 'checked="checked"';
            }
            echo '<label><input type="checkbox" name="post_types[]" value="'.$key.'" '.$checked.' disabled="disabled"' .' /> ' . ucfirst($val) . '</label><br/>';
        }
    }
    echo '<br/>';
    echo '<p class="description">' . str_replace('%link%', $motopressCESettings['lite_upgrade_url'], $motopressCELang->CEUpgradeText) . '</p>';
}

function motopressCERolesSettingsSecTxt(){}
function motopressCERolesSettingsFields(){
    global $motopressCELang, $motopressCESettings;
    global $wp_roles;
    if ( ! isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }
    $disabledRoles = get_option('motopress-ce-disabled-roles', array());

    $roles = $wp_roles->get_names();
    unset($roles['administrator']);

    foreach ($roles as $role => $roleName ){
        $checked = '';
        if (in_array($role, $disabledRoles)){
            $checked = 'checked="checked"';
        }
        echo '<label><input type="checkbox" name="disabled_roles[]" value="'.$role.'" '.$checked.' disabled="disabled"' .' /> '.$roleName.'</label><br/>';
    }

    echo '<p class="description">' . strtr($motopressCELang->CEOptRolesSettingsDescription, array('%BrandName%' => $motopressCESettings['brand_name'])) . str_replace("%link%", $motopressCESettings['lite_upgrade_url'], $motopressCELang->CEUpgradeText) . '</p>';
}

function motopressCESpellcheckSecTxt(){}
function motopressCESpellcheckFields(){
    global $motopressCELang;

    $spellcheck_enable = get_option('motopress-ce-spellcheck-enable', '1');

    $checked = '';
    if ($spellcheck_enable) {
        $checked = 'checked="checked"';
    }
    echo '<label><input type="checkbox" name="spellcheck_enable" ' . $checked . ' />' . $motopressCELang->CEOptSpellcheck . '</label><br/>';
    echo '<p class="description">'.$motopressCELang->CEOptSpellcheckDescription.'</p>';
}

function motopressCEFixedRowWidthSecTxt() {}
function motopressCEFixedRowWidthFields() {
	global $motopressCELang;
	$fixedRowWidth = get_option('motopress-ce-fixed-row-width', 1170);
	echo '<input type="text" name="fixed_row_width" value="' . $fixedRowWidth . '" class="regular-text" />';
}

function motopressCECustomCSSSecTxt() {}
function motopressCECustomCSSFields() {
    global $motopressCELang, $motopressCESettings;

    if ( !$motopressCESettings['wp_upload_dir_error'] ) {
        if (!file_exists($motopressCESettings['plugin_upload_dir_path']))
            mkdir($motopressCESettings['plugin_upload_dir_path'], 0777);

        clearstatcache();
        if ( is_writable($motopressCESettings['plugin_upload_dir_path']) ) {
            $css_file = $motopressCESettings['custom_css_file_path'];
            if ( file_exists($css_file) ) {
                $cssValue = file_get_contents($css_file);
                $cssValue = esc_html( $cssValue );
            }else {
                $cssValue = '';
            }
            echo '<label><textarea name="custom_css" cols="40" rows="10" style="width:100%;max-width:1000px;">'.$cssValue.'</textarea></label>';
            echo '<p class="description">'.$motopressCELang->CETextareaCustomCSSDescription.'</p>';
        }else {
            $subdirNotWritable = $motopressCELang->CEOptMsgNotWritable;
            $subdirNotWritable = str_replace( '%dir%', $motopressCESettings['plugin_upload_dir_path'], $subdirNotWritable );
            echo $subdirNotWritable;
        }
    }else {
        $updirNotWritable = $motopressCELang->CEOptMsgNotWritable;
        $updirNotWritable = str_replace( '%dir%', $motopressCESettings['wp_upload_dir'], $updirNotWritable );
        echo $updirNotWritable;
    }
}

function motopressCEExcerptSecTxt() {}
function motopressCEExcerptFields() {
    global $motopressCELang;

    // Excerpt shortcode
    $excerptShortcode = get_option('motopress-ce-excerpt-shortcode', '1');
    $checked = '';
    if ($excerptShortcode) {
        $checked = ' checked="checked"';
    }
    echo '<label><input type="checkbox" name="excerpt_shortcode"' . $checked . '>' . $motopressCELang->CEOptExcerptShortcode . '</label><br>';

    // Save excerpt
    $saveExcerpt = get_option('motopress-ce-save-excerpt', '1');
    $checked = '';
    if ($saveExcerpt) {
        $checked = ' checked="checked"';
    }
    echo '<label><input type="checkbox" name="save_excerpt"' . $checked . '>' . $motopressCELang->CEOptSaveExcerpt . '</label>';
}

function motopressCEGoogleFontsSecTxt() {}
function motopressCEGoogleFontsFields() {
    global $motopressCELang, $motopressCESettings;
    clearstatcache();
    $error = motopress_check_google_font_dir_permissions(true);

    if (!isset($error['error'])) {
        $prefix = $motopressCESettings['google_font_classes_prefix'];
        $fonts = array();
        $googleFontsJSON = file_get_contents(dirname(__FILE__) . '/googlefonts/webfonts.json' );
        if ($googleFontsJSON) {
            $googleFonts = json_decode( $googleFontsJSON, true );
            if (!is_null($googleFonts) && isset($googleFonts['items'])) {
                foreach($googleFonts['items'] as $googleFont) {
                    $id = strtolower( str_replace( ' ', '_', $googleFont['family'] ) );
                    $fonts[$id] = $googleFont;
                }
            }
        }
        $googleFontsJSON = json_encode($fonts);
        wp_register_script('mp-google-font-class-manager', $motopressCESettings['plugin_dir_url'] . 'includes/js/mp-google-font-class-manager.js', array('jquery'), $motopressCESettings['plugin_version']);
        wp_localize_script('mp-google-font-class-manager', 'motopressGoogleFontsJSON', $googleFontsJSON);
        wp_enqueue_script('mp-google-font-class-manager');
        $googleFontClasses = get_option('motopress_google_font_classes', array());
        echo '<p>' . strtr($motopressCELang->CEOptGoogleFontsDesc, array('%BrandName%' => $motopressCESettings['brand_name'])) . '</p><br/>';
        echo '<p>' . $motopressCELang->CEOptGoogleFontsTip . '</p><br/>';
        echo '<div id="motopress-google-font-class-manager">';
        echo '<input type="hidden" name="google_font_dir_writable" value="true">';
        foreach ($googleFontClasses as $className => $googleFontClass) {
            $variantCheckboxes = '';
            $subsetCheckboxes = '';
            echo '<div class="mp-google-font-class-entry">';
            echo '<div class="mp-google-font-class-name-container">';
            echo '<span class="mp-google-font-class-name">' . $className . '</span>';
            echo '<button disabled="disabled" class="mp-remove-google-font-class-entry">' . $motopressCELang->CEOptGoogleFontsRemoveClass . '</button>';
            echo '</div>';
            echo '<div class="mp-google-font-details">';
            echo '<label class="mp-google-fonts-list-container">'.$motopressCELang->CEOptGoogleFontsFamily.'<select class="mp-google-fonts-list" name="motopress_google_font_classes[' . $className . '][family]">';
            foreach ($googleFonts['items'] as $googleFont) {
                if ( $googleFontClass['family'] === $googleFont['family'] ) {
                    $selected = ' selected="selected"';
                    $variantCheckboxes = '<div class="mp-google-font-variants"><label>'.$motopressCELang->CEOptGoogleFontsVariants.'</label>';
                    foreach($googleFont['variants'] as $variant) {
                        $checked = isset($googleFontClass['variants']) && in_array($variant, $googleFontClass['variants']) ? ' checked="checked"' : '';
                        $variantCheckboxes .= '<label><input type="checkbox" ' . $checked . ' name="motopress_google_font_classes[' . $className . '][variants][]" value="' . $variant . '">'.$variant.'</label>';
                    }
                    $variantCheckboxes .= '</div>';
                    $subsetCheckboxes = '<div class="mp-google-font-subsets"><label>'.$motopressCELang->CEOptGoogleFontsSubsets.'</label>';
                    foreach($googleFont['subsets'] as $subset) {
                        $checked = isset($googleFontClass['subsets']) && in_array($subset, $googleFontClass['subsets']) ? ' checked="checked"' : '';
                        $subsetCheckboxes .= '<label><input type="checkbox" ' . $checked . ' name="motopress_google_font_classes[' . $className . '][subsets][]" value="' . $subset . '">'.$subset.'</label>';
                    }
                    $subsetCheckboxes .= '</div>';
                } else {
                    $selected = '';
                }
                echo '<option value="' . $googleFont['family'] . '" ' . $selected . '>' . $googleFont['family'] . '</option>';
            }
            echo '</select></label>';
            echo $variantCheckboxes;
            echo $subsetCheckboxes;
            echo '</div>';
            echo '</div>';
        }
        echo '<div id="motopress-google-font-class-manager-tools">';
        echo '<label class="mp-google-fonts-list-container">'.$motopressCELang->CEOptGoogleFontsFamily.'<select class="mp-google-fonts-list">';
        foreach($googleFonts['items'] as $googleFont){
            echo '<option value="' . $googleFont['family'] . '">' . $googleFont['family'] . '</option>';
        }
        echo '</select></label>';
        echo '<div class="mp-google-font-variants"><label>'.$motopressCELang->CEOptGoogleFontsVariants.'</label></div>';
        echo '<div class="mp-google-font-subsets"><label>'.$motopressCELang->CEOptGoogleFontsSubsets.'</label></div>';
        echo '<button class="mp-remove-google-font-class-entry">' . $motopressCELang->CEOptGoogleFontsRemoveClass . '</button>';
        echo '<p class="mp-google-font-add-new-label">'.$motopressCELang->CEOptGoogleFontsAddNewStyle.'</p>';
        echo '<label for="class-name">'.$motopressCELang->CEOptGoogleFontsCustomClassNameLabel.'</label>';
        echo '<input disabled="disabled" id="class-name" class="class-name" type="text" />';
        echo '<button disabled="disabled" class="mp-create-google-font-class-entry">' . $motopressCELang->CEOptGoogleFontsCreateClass . '</button>';
        echo '<p class="description mp-google-font-custom-style-desc">'.$motopressCELang->CEOptGoogleFontsCustomClassNameDesc . ' ' . str_replace("%link%", $motopressCESettings['lite_upgrade_url'], $motopressCELang->CEUpgradeText).'</p>';
        echo '<p class="font-name-info"><span class="wrong-class-name hidden">'.$motopressCELang->CEOptGoogleFontsWrongClassName.'</span><span class="duplicate-class-name hidden">'.$motopressCELang->CEOptGoogleFontsDuplicateClassName.'</span></p>';
        echo '</div>';
        echo '</div>';
    } else {
        echo $error['error'];
    }
}

function motopressCEHideSecTxt() {}
function motopressCEHideFields() {
    global $motopressCELang, $motopressCESettings;

    $hideOption = get_site_option('motopress-ce-hide-options-on-subsites', '0');

    $checked = '';
    if ($hideOption) {
        $checked = ' checked="checked"';
    }
    echo '<label><input type="checkbox" name="hide_options"' . $checked . '>' . strtr($motopressCELang->CEOptHideOptions, array('%BrandName%' => $motopressCESettings['brand_name'])) . '</label><br>';
}

function motopressCESettingsSave() {
	global $motopressCESettings;
	$pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : $motopressCESettings['plugin_short_name'];

	if ($pluginId === $motopressCESettings['plugin_short_name']) {
		if (!empty($_POST)) {
			global $motopressCESettings;

			// Language
			if (isset($_POST['language']) && !empty($_POST['language'])) {
				$language = $_POST['language'];
				update_option('motopress-language', $language);
				$motopressCESettings['lang'] = motopressCEGetLang();
			}
			

			// Spellcheck
			if (isset($_POST['spellcheck_enable'])) {
				$spellcheck_enable = '1';
			} else {
				$spellcheck_enable = '0';
			}
			update_option('motopress-ce-spellcheck-enable', $spellcheck_enable);

			// Custom CSS
			if (isset($_POST['custom_css'])) {

				if (!file_exists($motopressCESettings['plugin_upload_dir_path']))
					mkdir($motopressCESettings['plugin_upload_dir_path'], 0777);

				$current_css = $_POST['custom_css'];

				// css file creation & rewrite
				if (!empty($current_css)) {
					$content = stripslashes($current_css);
					clearstatcache();
					if (is_writable($motopressCESettings['wp_upload_dir']))
						file_put_contents($motopressCESettings['custom_css_file_path'], $content);
				} else {
					if (file_exists($motopressCESettings['custom_css_file_path'])) {
						clearstatcache();
						if (is_writable($motopressCESettings['wp_upload_dir']))
							unlink($motopressCESettings['custom_css_file_path']);
					}
				}
				// css file deletion END
			}

			// Excerpt shortcode
			if (isset($_POST['excerpt_shortcode']) && $_POST['excerpt_shortcode']) {
				$excerptShortcode = '1';
			} else {
				$excerptShortcode = '0';
			}
			update_option('motopress-ce-excerpt-shortcode', $excerptShortcode);

			// Save excerpt
			if (isset($_POST['save_excerpt']) && $_POST['save_excerpt']) {
				$saveExcerpt = '1';
			} else {
				$saveExcerpt = '0';
			}
			update_option('motopress-ce-save-excerpt', $saveExcerpt);

			// Hide options
			if (is_multisite() && is_main_site() && is_super_admin()) {
				if (isset($_POST['hide_options']) && $_POST['hide_options']) {
					$hideOptions = '1';
				} else {
					$hideOptions = '0';
				}
				update_site_option('motopress-ce-hide-options-on-subsites', $hideOptions);
			}

			if (isset($_POST['fixed_row_width'])) {
				$fixedRowWidth = filter_input(INPUT_POST, 'fixed_row_width', FILTER_VALIDATE_INT, array(
					'options'=>array(
						'min_range' => 1
					)
				));
				if ($fixedRowWidth) {
					update_option('motopress-ce-fixed-row-width', $fixedRowWidth);
				}
			}

			//Google Fonts Classes
			if (isset($_POST['google_font_dir_writable'])) {
				$fontClasses = isset($_POST['motopress_google_font_classes']['opensans']) ? array( 'opensans' => $_POST['motopress_google_font_classes']['opensans'] ) : array();
				saveGoogleFontClasses($fontClasses);
			}

			wp_redirect(add_query_arg(array('page' => $_GET['page'], 'plugin' => $_GET['plugin'], 'settings-updated' => 'true'), admin_url('admin.php')));
		}

	} else {
		do_action('admin_mpce_settings_save-' . $pluginId);
	}
}

function saveGoogleFontClasses($fontClasses){
    global $motopressCESettings;
    clearstatcache();
    $error = motopress_check_google_font_dir_permissions(true);
    if (!isset($error['error'])) {
        $prefix = $motopressCESettings['google_font_classes_prefix'];
        $oldFontClasses = get_option('motopress_google_font_classes', array());
        //remove unused files
        $removeClasses = array_diff_key($oldFontClasses, $fontClasses);
        foreach($removeClasses as $removeClass) {
            if (isset($removeClass['file']) && file_exists($motopressCESettings['google_font_classes_dir'] . $removeClass['file'])){
                if ( is_writable($motopressCESettings['google_font_classes_dir'] . $removeClass['file']) ){
                    unlink($motopressCESettings['google_font_classes_dir'] . $removeClass['file']);
                    clearstatcache();
                }
            }
        }
        foreach ($fontClasses as $fontClassName => $fontClass) {
            if (isset($oldFontClasses[$fontClassName])
                && ( $oldFontClasses[$fontClassName]['family'] === $fontClass['family'])
                && (
                    ( isset($oldFontClasses[$fontClassName]['variants']) && isset($fontClass['variants']) && $oldFontClasses[$fontClassName]['variants'] == $fontClass['variants'] )
                    ||
                    ( !isset($oldFontClasses[$fontClassName]['variants']) && !isset($fontClass['variants']) )
                )
                && (
                    ( isset($oldFontClasses[$fontClassName]['subsets']) && isset($fontClass['subsets']) && $oldFontClasses[$fontClassName]['subsets'] == $fontClass['subsets'] )
                    ||
                    ( !isset($oldFontClasses[$fontClassName]['subsets']) && !isset($fontClass['subsets']) )
                )
            ) {
                $fontClasses[$fontClassName] = $oldFontClasses[$fontClassName];
            } else {
                $importFamily = str_replace(' ', '+', $fontClass['family']);
                $importSubsets = '';
                $importVariants = '';
                if (isset($fontClass['subsets'])){
                    $importSubsets = '&subset=' . join(',', $fontClass['subsets']);
                }
                if (isset($fontClass['variants'])){
                    $importVariants = ':' . join(',', $fontClass['variants']);
                }
                $content = '@import url(\'http://fonts.googleapis.com/css?family=' . $importFamily . $importVariants . $importSubsets . '\');' . "\n";
                $content .= '.' . $prefix . $fontClassName . ' *{'
                        . 'font-family: ' . $fontClass['family'] . ';'
                        . '}' . "\n";
                if (isset($fontClass['variants'])) {
                    foreach($fontClass['variants'] as $variant) {
                        $fontStyle = stripos($variant, 'italic') !== false ? 'font-style:italic !important;' : 'font-style:normal !important;';
                        $emFontStyle = 'font-style:italic !important;';
                        $weight = preg_replace('/\D/', '', $variant);
                        if ($weight == '') {
                            $weight = '400';
                        }
                        if ($weight < 400) {
                            $strongFontWeight = ' font-weight: 400 !important;';
                        } else {
                            $strongFontWeight = ' font-weight: 700 !important;';
                        }
                        $fontWeight = 'font-weight:' . $weight . ' !important;';
                        $content .= '.' . $prefix . $fontClassName . '-' . $variant . ' *{'
                                . 'font-family : ' . $fontClass['family'] . ';}'
                                . '.' . $prefix . $fontClassName . '-' . $variant . ' *{'
                                . $fontStyle
                                . $fontWeight
                                . '}'
                                . '.' . $prefix . $fontClassName . '-' . $variant . ' strong{'
                                . $strongFontWeight
                                . '}'
                                . '.' . $prefix . $fontClassName . '-' . $variant . ' em{'
                                . $emFontStyle
                                . '}' . "\n";
                    }
                }
                $fontClasses[$fontClassName]['css'] = $content;
                $fontClasses[$fontClassName]['fullname'] = $prefix . $fontClassName;

                $filename = $fontClassName . '.css';
                if (false !== file_put_contents($motopressCESettings['google_font_classes_dir'] . $filename, $content)) {
                    $fontClasses[$fontClassName]['file'] = $filename;
                } else {
                    unset($fontClasses[$fontClassName]);
                }
            }
        }
        update_option('motopress_google_font_classes',$fontClasses);
    }
}

function motopressCELicense() {
    global $motopressCESettings, $motopressCELang;

    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error(
            'motopressLicense',
            esc_attr('settings_updated'),
            $motopressCELang->CEOptMsgUpdated,
            'updated'
        );
    }

    $pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : $motopressCESettings['plugin_short_name'];

    echo '<div class="wrap">';
    echo '<h1>' . $motopressCELang->CELicense . '</h1>';

    // Tabs
	$tabs = $motopressCESettings['license_tabs'];
	if (count($tabs)) {
	    echo '<h2 class="nav-tab-wrapper">';
		foreach ($tabs as $tabId => $tab) {
			$class = ($tabId == $pluginId) ? ' nav-tab-active' : '';
			echo '<a href="' . esc_url(add_query_arg(array('page' => $_GET['page'], 'plugin' => $tabId), admin_url('admin.php'))) . '" class="nav-tab' . $class . '">' . esc_html($tab['label']) . '</a>';
		}
	    echo '</h2>';

	    if (array_key_exists($pluginId, $tabs)) {
	        $callbackFunc = $tabs[$pluginId]['callback'];
	        if (!empty($callbackFunc)) {
	            if (
	                (is_string($callbackFunc) && function_exists($callbackFunc)) ||
	                (is_array($callbackFunc) && count($callbackFunc) === 2 && method_exists($callbackFunc[0], $callbackFunc[1]))
	            ) {
	                call_user_func($callbackFunc);
	            }
	        }
	    }
	}
    echo '</div>';
}



// check a license key
function edd_mpce_check_license($license) {
    global $motopressCESettings;
    $result = array(
        'errors' => array(),
        'data' => array()
    );
    $apiParams = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode($motopressCESettings['edd_mpce_item_name'])
    );

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($apiParams, $motopressCESettings['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

    if (is_wp_error($response)) {
        $errors = $response->get_error_codes();
        foreach ($errors as $key => $code) {
            $result['errors'][$code] = $response->get_error_message($code);
        }
        return $result;
    }

    $licenseData = json_decode(wp_remote_retrieve_body($response));

    if (!is_null($licenseData)) {
        $result['data'] = $licenseData;
    } else {
        $result['errors']['json_decode'] = 'Unable to decode JSON string.';
    }

    return $result;
}

function motopressCELicenseLoad() {
    global $motopressCESettings;
    $pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : $motopressCESettings['plugin_short_name'];

	if (
		empty($_POST)
		&& (
			!isset($_GET['plugin'])
			&& !array_key_exists($motopressCESettings['plugin_short_name'], $motopressCESettings['license_tabs'])
		)
	) {
		reset($motopressCESettings['license_tabs']);
		$_pluginId = key($motopressCESettings['license_tabs']);
		if ($_pluginId) {
			wp_redirect(add_query_arg(array('page' => $_GET['page'], 'plugin' => $_pluginId), admin_url('admin.php')));
		}
	}

    if ($pluginId === $motopressCESettings['plugin_short_name']) {
        if (!empty($_POST)) {
            $queryArgs = array('page' => $_GET['page']);

            if (isset($_POST['edd_mpce_license_key'])) {
                if (!check_admin_referer('edd_mpce_nonce', 'edd_mpce_nonce')) {
                    return;
                }
                $licenseKey = trim($_POST['edd_mpce_license_key']);
                motopressCESetLicense($licenseKey);
            }

            //activate
            if (isset($_POST['edd_license_activate'])) {
                if (!check_admin_referer('edd_mpce_nonce', 'edd_mpce_nonce')) {
                    return; // get out if we didn't click the Activate button
                }
                $licenseData = motopressCEActivateLicense();

                if ($licenseData === false)
                    return false;

                if (!$licenseData->success && $licenseData->error === 'item_name_mismatch') {
                    $queryArgs['item-name-mismatch'] = 'true';
                }
            }

            //deactivate
            if (isset($_POST['edd_license_deactivate'])) {
                // run a quick security check
                if (!check_admin_referer( 'edd_mpce_nonce', 'edd_mpce_nonce')) {
                    return; // get out if we didn't click the Activate button
                }

                $licenseData = motopressCEDeactivateLicense();

                if ($licenseData === false)
                    return false;
            }

            $queryArgs['settings-updated'] = 'true';
            wp_redirect(add_query_arg($queryArgs, get_admin_url() . 'admin.php'));
        }
    } else {
        do_action('admin_mpce_license_save-' . $pluginId);
    }
}

function motopressCESetAndActivateLicense($licenseKey){
    motopressCESetLicense($licenseKey);
    motopressCEActivateLicense();
}

function motopressCESetLicense($licenseKey){
    $oldLicenseKey = get_option('edd_mpce_license_key');
    if ($oldLicenseKey && $oldLicenseKey !== $licenseKey) {
        delete_option('edd_mpce_license_status'); // new license has been entered, so must reactivate
    }
    if (!empty($licenseKey)) {
        update_option('edd_mpce_license_key', $licenseKey);
    } else {
        delete_option('edd_mpce_license_key');
    }
}

function motopressCEActivateLicense(){
    global $motopressCESettings;
    $licenseKey = get_option('edd_mpce_license_key');

    // data to send in our API request
    $apiParams = array(
        'edd_action' => 'activate_license',
        'license' => $licenseKey,
        'item_name' => urlencode($motopressCESettings['edd_mpce_item_name']) // the name of our product in EDD
    );

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($apiParams, $motopressCESettings['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

    // make sure the response came back okay
    if (is_wp_error($response)) {
        return false;
    }

    // decode the license data
    $licenseData = json_decode(wp_remote_retrieve_body($response));

    // $licenseData->license will be either "active" or "inactive"
    update_option('edd_mpce_license_status', $licenseData->license);

    return $licenseData;
}

function motopressCEDeactivateLicense(){
    global $motopressCESettings;
    // retrieve the license from the database
    $licenseKey = get_option('edd_mpce_license_key');

    // data to send in our API request
    $apiParams = array(
        'edd_action' => 'deactivate_license',
        'license' => $licenseKey,
        'item_name' => urlencode($motopressCESettings['edd_mpce_item_name']) // the name of our product in EDD
    );

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($apiParams, $motopressCESettings['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

    // make sure the response came back okay
    if (is_wp_error($response)) {
        return false;
    }

    // decode the license data
    $licenseData = json_decode(wp_remote_retrieve_body($response));

    // $license_data->license will be either "deactivated" or "failed"
    if($licenseData->license == 'deactivated') {
        delete_option('edd_mpce_license_status');
    }
    return $licenseData;
}