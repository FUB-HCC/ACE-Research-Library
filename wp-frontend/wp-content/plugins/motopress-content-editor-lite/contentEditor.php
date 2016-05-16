<?php
function motopressCEAddTools() {
	global $motopressCESettings;

    require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/Access.php';
    $ceAccess = new MPCEAccess();
    global $isMotoPressCEPage;
    $isMotoPressCEPage = true;

    $motopressCELibrary = MPCELibrary::getInstance();

    $postType = get_post_type();
    $postTypes = get_option('motopress-ce-options', array('post', 'page'));

    $gridObjects = $motopressCELibrary->getGridObjects();
    $renderedShortcodes = array(
        'grid' => array(),
        'empty' => array()
    );

    // Rendered Grid Objects
    foreach(array($gridObjects['row']['shortcode'], $gridObjects['row']['inner'], $gridObjects['span']['shortcode'], $gridObjects['span']['inner']) as $shortcodeName) {
        $shortcode = generateShortcodeFromLibrary($shortcodeName);
        $renderedShortcodes['grid'][$shortcodeName] = do_shortcode($shortcode);
    }

    // Rendered Empty Spans
    foreach(array($gridObjects['span']['shortcode'], $gridObjects['span']['inner']) as $shortcodeName){
        $shortcode = generateShortcodeFromLibrary($shortcodeName, array('motopress-empty', 'mp-hidden-phone'));
        $renderedShortcodes['empty'][$shortcodeName] = do_shortcode($shortcode);
    }

    if (in_array($postType, $postTypes) && post_type_supports($postType, 'editor') && $ceAccess->hasAccess()) {
	    require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/ThemeFix.php';
	    $themeFix = new MPCEThemeFix(MPCEThemeFix::DEACTIVATE);

        global $motopressCELang;
        wp_localize_script('jquery', 'motopress', $motopressCESettings['motopress_localize']);
        wp_localize_script('jquery', 'motopressCE',
            array(
                'postID' => get_the_ID(),
//                'postPreviewUrl' => post_preview(),
                'nonces' => array(
                    'motopress_ce_get_wp_settings' => wp_create_nonce('wp_ajax_motopress_ce_get_wp_settings'),
//                    'motopress_ce_render_content' => wp_create_nonce('wp_ajax_motopress_ce_render_content'),
//                    'motopress_ce_remove_temporary_post' => wp_create_nonce('wp_ajax_motopress_ce_remove_temporary_post'),
//                    'motopress_ce_get_library' => wp_create_nonce('wp_ajax_motopress_ce_get_library'),
                    'motopress_ce_render_shortcode' => wp_create_nonce('wp_ajax_motopress_ce_render_shortcode'),
                    'motopress_ce_render_template' => wp_create_nonce('wp_ajax_motopress_ce_render_template'),
					'motopress_ce_render_shortcodes_string' => wp_create_nonce('wp_ajax_motopress_ce_render_shortcodes_string'),
                    'motopress_ce_get_attachment_thumbnail' => wp_create_nonce('wp_ajax_motopress_ce_get_attachment_thumbnail'),
                    'motopress_ce_colorpicker_update_palettes' => wp_create_nonce('wp_ajax_motopress_ce_colorpicker_update_palettes'),
                    'motopress_ce_render_youtube_bg' => wp_create_nonce('wp_ajax_motopress_ce_render_youtube_bg'),
                    'motopress_ce_render_video_bg' => wp_create_nonce('wp_ajax_motopress_ce_render_video_bg'),
                    'motopress_ce_get_translations' => wp_create_nonce('wp_ajax_motopress_ce_get_translations'),
                ),
                'settings' => array(
                    'wp' => $motopressCESettings,
                    'library' => $motopressCELibrary->getData(),
                    'translations' => $motopressCELang
                ),
                'rendered_shortcodes' => $renderedShortcodes,
		        'info' => array(
					'is_headway_themes' => $themeFix->isHeadwayTheme()
		        ),
				'styleEditor' => MPCECustomStyleManager::getLocalizeJSData()
            )
        );
        add_action('admin_head', 'motopressCEAddCEBtn');
        add_action('admin_footer', 'motopressCEHTML'); //admin_head

        motopressCECheckDomainMapping();

        wp_register_style('mpce-style',$motopressCESettings['plugin_dir_url'] . 'includes/css/style.css', null, $motopressCESettings['plugin_version']);
        wp_enqueue_style('mpce-style');

        wp_register_style('mpce', $motopressCESettings['plugin_dir_url'] . 'mp/ce/css/ce.css', null, $motopressCESettings['plugin_version']);
        wp_enqueue_style('mpce');

        $customPreloaderImageSrc = apply_filters('mpce_preloader_src', false);
        if ($customPreloaderImageSrc) {
            echo '<style type="text/css">#motopress-preload{background-image: url("' . esc_url($customPreloaderImageSrc) . '") !important;}</style>';
        }

        wp_register_script('mpce-knob', $motopressCESettings['plugin_dir_url'] . 'knob/jquery.knob.min.js', array(), $motopressCESettings['plugin_version']);
        wp_enqueue_script('mpce-knob');

        if (get_user_meta(get_current_user_id(), 'rich_editing', true) === 'false' && !wp_script_is('editor')) {
            wp_enqueue_script('editor');
        }

        wp_enqueue_script('wp-link');
    }
}

function generateShortcodeFromLibrary($shortcodeName, $customClasses = array()) {
    $motopressCELibrary = MPCELibrary::getInstance();
    $shortcodeObject = $motopressCELibrary->getObject($shortcodeName);
    $gridObjects = $motopressCELibrary->getGridObjects();
    $shortcode = '[' . $shortcodeName;
    foreach($shortcodeObject->getParameters() as $parameterName => $parameter) {
        if (isset($parameter['default']) && $parameter['default'] !== '') {
            $shortcode .= ' ' . $parameterName . '="' . $parameter['default'] . '"';
        }
    }
    $shortcodeStyles = $shortcodeObject->getStyles();
    $styleClassesArr = isset($shortcodeStyles['default']) && !empty($shortcodeStyles['default']) ? array_merge($customClasses, $shortcodeStyles['default']) : $customClasses;
    if (!empty($styleClassesArr)) {
        $shortcode .= ' mp_style_classes="';
        $shortcode .= implode(' ', $styleClassesArr);
        $shortcode .= '"';
    }

    // Add column width parameter
//    if (in_array($shortcodeName, array($gridObjects['span']['shortcode'], $gridObjects['span']['inner']))) {
//        $shortcode .= ' ' . $gridObjects['span']['attr'] . '="' . $gridObjects['row']['col'] . '"';
//    }

    $shortcode .= ']<div class="motopress-filler-content"></div>[/' . $shortcodeName . ']';
    return $shortcode;
}

function motopressCECheckDomainMapping() {
    global $wpdb;

    if (is_multisite()) {
        if (is_plugin_active('domain-mapping/domain-mapping.php') || is_plugin_active('wordpress-mu-domain-mapping/domain_mapping.php')) {
            $blogDetails = get_blog_details();
            $mappedDomains = $wpdb->get_col(sprintf("SELECT domain FROM %s WHERE blog_id = %d ORDER BY id ASC", $wpdb->dmtable, $blogDetails->blog_id));
            if (!empty($mappedDomains)) {
                if (!in_array(parse_url($blogDetails->siteurl, PHP_URL_HOST), $mappedDomains)) {
                    add_action('admin_notices', 'motopressCEDomainMappingNotice');
                }
            }
        }
    }
}

function motopressCEDomainMappingNotice() {
    global $motopressCELang;
    $linkDomainMapping = apply_filters('mpce_link_domain_mapping', 'https://motopress.zendesk.com/hc/en-us/articles/200884839-WordPress-Multisite-domain-mapping-configuration');
    echo '<div class="error"><p>' . str_replace('%link%', esc_url($linkDomainMapping), $motopressCELang->CEDomainMapping) . '</p></div>';
}

function motopressCEHTML() {
    global $motopressCESettings;
    global $motopressCELang;
    global $pagenow;
    global $post;

//    global $post;
//    $nonce = wp_create_nonce('post_preview_' . $post->ID);
//    $url = add_query_arg( array( 'preview' => 'true', 'preview_id' => $post->ID, 'preview_nonce' => $nonce ), get_permalink($post->ID) );
//    echo '<a href="' . $url . '" target="wp-preview" title="' . esc_attr(sprintf(__('Preview “%s”'), $title)) . '" rel="permalink">' . __('Preview') . '</a>';
//    echo '<a href="' . post_preview() . '" target="wp-preview" title="' . esc_attr(sprintf(__('Preview “%s”'), $title)) . '" rel="permalink">' . __('Preview') . '</a>';

//    echo '<br/>';
//    echo $url;
//    echo '<br/>';
//    echo post_preview();

	$duplicateBtnTitle = $motopressCELang->CELiteTooltipText;
	$duplicateBtnAttrs = 'disabled="disabled"';
?>
    <div id="motopress-content-editor" style="display: none;">
        <div class="motopress-content-editor-navbar">
            <div class="navbar-inner">
                <div id="motopress-logo">
                    <?php $logoSrc = apply_filters('mpce_logo_src', $motopressCESettings['plugin_dir_url'] . 'images/logo.png?ver='.$motopressCESettings['plugin_version']);?>
                    <img src="<?php echo esc_url($logoSrc); ?>">
                </div>
                <div class="motopress-page-name">
                    <span id="motopress-post-type"><?php echo get_post_type() == 'page' ? $motopressCELang->CEPage : $motopressCELang->CEPost; ?></span>:
                    <span id="motopress-title"></span>
                    <input type="text" id="motopress-input-edit-title" class="motopress-hide" >
                </div>
	            <div class="pull-left motopress-control-btns motopress-leftbar-control-btns">
                    <button class="motopress-btn-red" id="mpce-add-widget" title="<?php echo $motopressCELang->CEAddWidgetBtnText; ?>"><?php echo $motopressCELang->CEAddWidgetBtnText; ?></button>
                </div>
                <div class="pull-left motopress-control-btns motopress-object-control-btns">
                    <button class="motopress-btn-default" id="motopress-content-editor-duplicate" title="<?php echo $duplicateBtnTitle; ?>" <?php echo $duplicateBtnAttrs; ?>><div class="motopress-content-editor-duplicate-icon"></div></button>
	                <button class="motopress-btn-default" id="motopress-content-editor-delete" title="<?php echo $motopressCELang->CEDeleteBtnText; ?>"><div class="motopress-content-editor-delete-icon"></div></button>
                </div>
                <div class="pull-right navbar-btns">					
                    <?php $isHideTutorials = apply_filters('mpce_hide_tutorials', false);
                    if (!$isHideTutorials) {
                        echo '<button class="motopress-btn-default btn-tutorials" id="motopress-content-editor-tutorials">?</button>';
                    }
                    ?>
                    <button class="motopress-btn-blue<?php if ($post->post_status === 'publish') echo ' motopress-ajax-update'; ?>" id="motopress-content-editor-publish"><?php echo $motopressCELang->CEPublishBtnText; ?></button>
                    <button class="motopress-btn-default<?php if ($pagenow !== 'post-new.php') echo ' motopress-ajax-update'; ?>" id="motopress-content-editor-save"><?php echo $motopressCELang->CESaveBtnText; ?></button>
					<button class="motopress-btn-default" id="motopress-content-editor-preview"><?php echo $motopressCELang->CEPreviewBtnText; ?></button>
					<button class="motopress-btn-default" id="motopress-content-editor-device-mode-preview" title="<?php echo $motopressCELang->CEResponsivePreview; ?>"><div></div></button>
                    <button class="motopress-btn-default" id="motopress-content-editor-close" title="<?php echo $motopressCELang->CECloseBtnText; ?>"><div></div></button>
                    <?php ?>
                    <button class="motopress-btn-red" id="motopress-content-editor-upgrade" onclick="window.open('<?php echo $motopressCESettings['lite_upgrade_url'] ?>','_blank')"><?php echo $motopressCELang->CEUpgradeBtnText; ?></button>
                    <?php ?>
                </div>
            </div>
        </div>
		<div id="motopress-content-editor-preview-device-panel" class="motopress-hide">
			<div>
				<div class="motopress-content-editor-preview-mode-btn motopress-content-editor-preview-desktop" data-mode="desktop"></div>
			</div>
<!--			<div>
				<div class="motopress-content-editor-preview-mode-btn motopress-content-editor-preview-tablet-landscape" data-mode="tablet-landscape"></div>
			</div>-->
			<div>
				<div class="motopress-content-editor-preview-mode-btn motopress-content-editor-preview-tablet" data-mode="tablet"></div>
			</div>
			<div>
				<div class="motopress-content-editor-preview-mode-btn motopress-content-editor-preview-phone" data-mode="phone"></div>
			</div>
<!--			<div>
				<div class="motopress-content-editor-preview-mode-btn motopress-content-editor-preview-phone-landscape" data-mode="phone-landscape"></div>
			</div>-->
			<div>
				<div class="motopress-content-editor-preview-edit"></div>
			</div>
		</div>

        <div id="motopress-flash"></div>

        <div id="motopress-content-editor-scene-wrapper">
	        <?php
	        $iframeSrc = get_permalink($post->ID);

	        //@todo: fix protocol for http://codex.wordpress.org/Administration_Over_SSL
	        //fix different site (WordPress Address) and home (Site Address) url for iframe security
	        $siteUrl = get_site_url();
	        $homeUrl = get_home_url();
	        $siteUrlArr = parse_url($siteUrl);
	        $homeUrlArr = parse_url($homeUrl);
	        if ($homeUrlArr['scheme'] !== $siteUrlArr['scheme'] || $homeUrlArr['host'] !== $siteUrlArr['host']) {
		        $iframeSrc = str_replace($homeUrl, $siteUrl, $iframeSrc);
	        }

	        // Fix for Domain Mapping plugin (separate frontend and backend domains)
	        if (is_plugin_active('domain-mapping/domain-mapping.php')) {
		        $iframeSrc = add_query_arg('dm', 'bypass', $iframeSrc);
	        }

	        $iframeSrc = add_query_arg(array('mpce-post-id' => $post->ID), $iframeSrc);
	        $iframeSrc = add_query_arg(array('motopress-ce' => '1'), $iframeSrc);
	        ?>
	        <form id="mpce-form" action="<?php echo $iframeSrc; ?>" method="POST" target="motopress-content-editor-scene">
		        <div class="mpce-form-fields">
			        <input type="hidden" name="mpce_title" />
			        <textarea name="mpce_editable_content"></textarea>
			        <textarea name="mpce_viewable_content"></textarea>
			        <input type="hidden" name="mpce-post-id" value="<?php echo $post->ID; ?>" />
			        <input type="hidden" name="mpce_page_template" />
		        </div>
	        </form>
<!--            <iframe id="motopress-content-editor-scene" class="motorpess-content-editor-scene" name="motopress-content-editor-scene"></iframe>-->
        </div>

        <!-- Video Tutorials -->
        <div id="motopress-tutorials-modal" class="motopress-modal modal motopress-soft-hide fade">
            <div class="modal-header">
                <p id="tutsModalLabel" class="modal-header-label"><?php echo strtr($motopressCELang->CEHelpAndTuts, array('%BrandName%' => $motopressCESettings['brand_name'])); ?><button type="button" tabindex="0" class="close massive-modal-close" data-dismiss="modal" aria-hidden="true">&times;</button></p>
            </div>
            <div class="modal-body"></div>
        </div>

        <!-- Code editor -->        
        <div id="motopress-code-editor-modal" class="motopress-modal modal motopress-soft-hide fade" role="dialog" aria-labelledby="codeModalLabel" aria-hidden="true">
            <div class="modal-header">
                <p id="codeModalLabel" class="modal-header-label"><?php echo $motopressCELang->edit . ' ' . $motopressCELang->CECodeObjName; ?></p>
            </div>
            <div class="modal-body">
                <div id="motopress-code-editor-wrapper">
                    <?php
                        wp_editor('', 'motopresscodecontent', array(
                            'textarea_rows' => false,
                            'tinymce' => array(
                                'remove_linebreaks' => false,
                                'schema' => 'html5',
                                'theme_advanced_resizing' => false
                            )
                        ));
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button id="motopress-save-code-content" class="motopress-btn-blue"><?php echo $motopressCELang->CESaveBtnText; ?></button>
                <button class="motopress-btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $motopressCELang->CECloseBtnText; ?></button>
            </div>
        </div>

		
		
        <!-- Confirm -->
        <!--
        <div id="motopress-confirm-modal" class="motopress-modal modal motopress-soft-hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-header">
                <div class="motopress-close motopress-icon-remove" data-dismiss="modal" aria-hidden="true"></div>
                <p id="confirmModalLabel" class="modal-header-label"></p>
            </div>
            <div class="modal-body">
                <p id="confirmModalMessage"></p>
            </div>
            <div class="modal-footer">
                <button id="motopress-confirm-yes" class="motopress-btn-blue"><?php //echo $motopressCELang->yes; ?></button>
                <button class="motopress-btn-default" data-dismiss="modal" aria-hidden="true"><?php //echo $motopressCELang->no; ?></button>
            </div>
        </div>
        -->
    </div>

    <div id="motopress-preload">
        <input type="text" id="motopress-knob">

        <div id="motopress-error">
            <div id="motopress-error-title"><?php echo $motopressCELang->CEErrorTitle; ?></div>
            <div id="motopress-error-message">
                <div id="motopress-system">
                    <p id="motopress-browser"></p>
                    <p id="motopress-platform"></p>
                </div>
            </div>
            <div class="motopress-terminate">
                <button id="motopress-terminate" class="motopress-btn-default"><?php echo $motopressCELang->CETerminate; ?></button>
            </div>
        </div>
        <script type="text/javascript">
            var MP = {
                Error: {
                    terminate: function() {
                        jQuery('html').css({
                            overflow: '',
                            paddingTop: 32
                        });
                        jQuery('body > #wpadminbar').prependTo('#wpwrap > #wpcontent');
                        //jQuery('#wpwrap').show();
                        var mpce = jQuery('#motopress-content-editor');
                        mpce.siblings('.motopress-hide').removeClass('motopress-hide');
                        //jQuery('#wpwrap').css('height', '');
                        jQuery('#wpwrap').height('');
                        //jQuery('#wpwrap').children(':not(#wpcontent)').removeClass('motopress-wpwrap-hidden');
                        //jQuery('#wpwrap > #wpcontent').children(':not(#wpadminbar)').removeClass('motopress-wpwrap-hidden');
                        var preload = jQuery('#motopress-preload');
                        preload.hide();
                        var error = preload.children('#motopress-error');
                        error.find('#motopress-system').prevAll().remove();
                        error.hide();
                        mpce.hide();
                        jQuery(window).trigger('resize'); //fix tinymce toolbar (wp v4.0)
                    },
                    log: function(e) {
                        console.group('CE error');
                            console.warn('Name: ' + e.name);
                            console.warn('Message: ' + e.message);
                            if (e.hasOwnProperty('fileName')) console.warn('File: ' + e.fileName);
                            if (e.hasOwnProperty('lineNumber')) console.warn('Line: ' + e.lineNumber);
                            console.warn('Browser: ' + navigator.userAgent);
                            console.warn('Platform: ' + navigator.platform);
                        console.groupEnd();

                        var error = jQuery('#motopress-preload > #motopress-error');
                        var text = e.name + ': ' + e.message + '.';
                        if (e.hasOwnProperty('fileName')) {
                            text += ' ' + e.fileName;
                        }
                        if (e.hasOwnProperty('lineNumber')) {
                            text += ':' + e.lineNumber;
                        }
                        error.find('#motopress-system').before(jQuery('<p />', {text: text}));
                        error.show();
                    }
                }
            };

            jQuery(document).ready(function($) {
                $('#motopress-knob').knob({
                    readOnly: true,
                    displayInput: false,
                    thickness: 0.05,
                    fgColor: '#d34937',
                    width: 136,
                    height: 136
                });

                $('#motopress-system')
                    .children('#motopress-browser').text('Browser: ' + navigator.userAgent)
                    .end()
                    .children('#motopress-platform').text('Platform: ' + navigator.platform);

                $('#motopress-terminate').on('click', function() {
                    MP.Error.terminate();
                });
            });
        </script>
    </div>

<?php

}

function motopressCEAddCEBtn() {
    global $motopressCESettings;
    global $motopressCELang;
    global $post;
    global $motopressCEIsjQueryVer;
    global $wp_version;
	$post_status = get_post_status(get_the_ID());
    $CEButtonText = apply_filters('mpce_button_text', strtr($motopressCELang->CEButton, array('%BrandName%' => $motopressCESettings['brand_name'])));
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            if (!MPCEBrowser.IE && !MPCEBrowser.Opera) {
	            var $form = $('#mpce-form'),
		            draftSaved = false;
                var motopressCEButton = $('<input />', {
                    type: 'button',
                    id: 'motopress-ce-btn',
                    'class': 'wp-core-ui button-primary',
                    value: '<?php echo $CEButtonText; ?>',
                    'data-post-id' : '<?php echo $post->ID; ?>',
                    'data-post-status' : '<?php echo $post_status; ?>',
                    disabled: 'disabled'
                }).insertAfter($('div#titlediv'));
                <?php if (extension_loaded('mbstring')) { ?>
                    <?php if ($motopressCEIsjQueryVer) { ?>
                        var preloader = $('#motopress-preload');
                        motopressCEButton.on('click', function() {
							
	                        $('#motopress-content-editor-scene').remove();
	                        $form.after('<iframe id="motopress-content-editor-scene" class="motorpess-content-editor-scene" name="motopress-content-editor-scene"></iframe>');

                            //console.time('ce');
                            //console.profile();

                            preloader.show();

	                        if (!draftSaved) {
	                        <?php if ($post_status == 'auto-draft') { ?>

		                        <?php if (version_compare($wp_version, '3.6', '<')) { ?>
		                        var editor = typeof tinymce !== 'undefined' && tinymce.get('content');
		                        if (editor && !editor.isHidden()) editor.save();
		                        var postData = {
			                        post_title: $('#title').val() || '',
			                        content: $('#content').val() || '',
			                        excerpt: $('#excerpt').val() || ''
		                        };
		                        <?php } else { ?>
		                        var postData = wp.autosave.getPostData();
		                        <?php } ?>

		                        if (!postData.content.length && !postData.excerpt.length && !$.trim(postData.post_title).length) {
			                        var noTitle = '<?php _e('(no title)'); ?>';
			                        if (!noTitle) noTitle = '<?php echo $motopressCELang->CEEmptyPostTitle; ?>';
			                        $('#title').val(noTitle);
			                        $('form[name="post"] #title-prompt-text').addClass('screen-reader-text');
			                        draftSaved = true;
		                        }

	                        <?php } ?>
	                        }
//                            sessionStorage.setItem('motopressPluginAutoSaved', false);

                            if (typeof CE === 'undefined') {
                                var head = $('head')[0];
                                var stealVerScript = $('<script />', {
                                    text: 'var steal = { production: "mp/ce/production.js" + motopress.pluginVersionParam };'
                                })[0];
                                head.appendChild(stealVerScript);
                                var script = $('<script />', {
                                    src: '<?php echo $motopressCESettings["plugin_dir_url"]; ?>' + 'steal/steal.production.js?mp/ce'
                                })[0];
                                head.appendChild(script);

                            } else {
								MP.Editor.myThis.open();
                            }
                        });

                        function mpceOnEditorInit() {
                            motopressCEButton.removeAttr('disabled');
                            if (pluginAutoOpen) {
                                sessionStorage.setItem('motopressPluginAutoOpen', false);
                                motopressCEButton.click();
                            }
                        }

                        var editorState = "<?php echo wp_default_editor(); ?>";
                        var pluginAutoOpen = sessionStorage.getItem('motopressPluginAutoOpen');
                        var paramPluginAutoOpen = ('<?php if (isset($_GET['motopress-ce-auto-open']) && $_GET['motopress-ce-auto-open']) echo $_GET['motopress-ce-auto-open']; ?>' === 'true') ? true : false; //fix different site (WordPress Address) and home (Site Address) url for sessionStorage
                        pluginAutoOpen = ((pluginAutoOpen && pluginAutoOpen === 'true') || paramPluginAutoOpen) ? true : false;
                        if (pluginAutoOpen) preloader.show();
						
						var tinyMCEEditorInitedDefer = $.Deferred();
						motopressCE.tinyMCEEditorInited = tinyMCEEditorInitedDefer.promise();	
						if (tinyMCE.majorVersion === '4') {
							tinyMCE.on('AddEditor', function(args){
								if(args.editor.id === 'content'){
									args.editor.on('init', function(ed){
										tinyMCEEditorInitedDefer.resolve(args.editor);
									});
								}
							});
						} else {
							tinyMCE.onAddEditor.add(function(mce, ed) {
								if (ed.editorId === 'content') {
									ed.onInit.add(function(ed) {
										tinyMCEEditorInitedDefer.resolve(ed);
									});
								}
							});
						}

                        if (typeof tinyMCE !== 'undefined' && editorState === 'tinymce') {
                            $.when(motopressCE.tinyMCEEditorInited).done(function(){
								mpceOnEditorInit();
							});
                        } else {
                            mpceOnEditorInit();
                        }
                    <?php } else {
                        add_action('admin_notices', 'motopressCEIsjQueryVerNotice');
                    } // endif jquery version check
                } else {
                    add_action('admin_notices', 'motopressCEIsMBStringEnabledNotice');
                }?>
            }
        });
    </script>
    <?php
    $isHideNativeEditor = apply_filters('mpce_hide_native_editor', false);
    if ($isHideNativeEditor) { ?>
    <style type="text/css">
        #postdivrich{
            display: none;
        }
    </style>
    <?php
    }
}

function motopressCEIsjQueryVerNotice() {
    global $motopressCELang;
    echo '<div class="error"><p>' . strtr($motopressCELang->jQueryVerNotSupported, array('%minjQueryVer%' => MPCERequirements::MIN_JQUERY_VER, '%minjQueryUIVer%' => MPCERequirements::MIN_JQUERYUI_VER)) . '</p></div>';
}

function motopressCEIsMBStringEnabledNotice() {
    global $motopressCELang, $motopressCESettings;
    echo '<div class="error"><p>' . strtr($motopressCELang->MBStringNotEnabled, array('%BrandName%' => $motopressCESettings['brand_name'])) . '</p></div>';
}

require_once $motopressCESettings['plugin_dir_path'] . 'includes/getWpSettings.php';
add_action('wp_ajax_motopress_ce_get_wp_settings', 'motopressCEGetWpSettings');
if (!isset($motopressCERequirements)) $motopressCERequirements = new MPCERequirements();
if (!isset($motopressCELang)) $motopressCELang = motopressCEGetLanguageDict();
require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/renderShortcode.php';
add_action('wp_ajax_motopress_ce_render_shortcode', 'motopressCERenderShortcode');
require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/renderTemplate.php';
add_action('wp_ajax_motopress_ce_render_template', 'motopressCERenderTemplate');
require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/renderShortcodesString.php';
add_action('wp_ajax_motopress_ce_render_shortcodes_string', 'motopressCERenderShortcodeString');
require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/getAttachmentThumbnail.php';
add_action('wp_ajax_motopress_ce_get_attachment_thumbnail', 'motopressCEGetAttachmentThumbnail');
require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/updatePalettes.php';
add_action('wp_ajax_motopress_ce_colorpicker_update_palettes', 'motopressCEupdatePalettes');
add_action('wp_ajax_motopress_ce_render_youtube_bg', array('MPCEShortcode', 'renderYoutubeBackgroundVideo'));
add_action('wp_ajax_motopress_ce_render_video_bg', array('MPCEShortcode', 'renderHTML5BackgroundVideo'));