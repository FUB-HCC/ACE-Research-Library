<?php

function motopressCERenderContent($content) {
//    require_once dirname(__FILE__).'/../verifyNonce.php';
    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../access.php';
    require_once dirname(__FILE__).'/../Requirements.php';
    require_once dirname(__FILE__).'/../functions.php';
    require_once dirname(__FILE__).'/../getLanguageDict.php';

	$content = trim($content);

    if (!empty($content)) {
        $content = motopressCEWrapOuterCode($content);
    }
    global $motopressCEWPAttachmentDetails;
    $motopressCEWPAttachmentDetails = array();
    $output = motopressCEParseObjectsRecursive($content);

	$output = '<div class="motopress-content-wrapper">' . $output . '</div>';
//	update_post_meta($post_id, '_mpce_content', '<div class="motopress-content-wrapper">' . $output . '</div>');

	return $output;
}

function motopressCEParseObjectsRecursive($matches, $parseContent = true) {
    $motopressCELibrary = MPCELibrary::getInstance();
    $regex = '/' . motopressCEGetMPShortcodeRegex() . '/s';

    if (is_array($matches)) {
		$shortcodeName = $matches[2];
		$attsStr = $matches[3];
		$content = $matches[5];

		$grid = $motopressCELibrary->getGridObjects();

		// Fix for cherry column shortcode with attr col_md="none"
		if ( is_plugin_active('motopress-cherryframework4/motopress-cherryframework4.php') && in_array($shortcodeName, array($grid['span']['shortcode'], $grid['span']['inner'])) ) {
			$regexp = '/(?:^' . $grid['span']['attr'] . '|\s' . $grid['span']['attr'] . ')\s*=\s*"([^"]*)"(?:\s|$)|(?:^' . $grid['span']['attr'] . '|\s' . $grid['span']['attr'] . ')\s*=\s*\'([^\']*)\'(?:\s|$)|(?:' . $grid['span']['attr'] . ')\s*=\s*([^\s\'"]+)(?:\s|$)/';
			$replacement = ' ' . $grid['span']['attr'] . '="' . $grid['row']['col'] . '" ';
			if (preg_match($regexp, $attsStr, $cherry_col)) {
				// $cherry_col must be in range 1 .. max col size
				$col = intval($cherry_col[1]);
				if ($col < 1 || $col > (int) $grid['row']['col']) {
					$attsStr = preg_replace($regexp, $replacement, $attsStr);
				}
			} else {
				$attsStr .= $replacement;
			}
		}

        if (!empty($content)) {
            $content = preg_replace('/^<\\/p>(.*)/', '${1}', $content);
            $content = preg_replace('/(.*)<p>$/', '${1}', $content);
        }
        $groupObjects = $motopressCELibrary->getGroupObjects();
        $parameters_str =' ' . MPCEShortcode::$attributes['parameters'];
        $unwrap = '';
        $atts = (array) shortcode_parse_atts($attsStr);
        
		// Fix Posts Grid template path
		if ($shortcodeName === MPCEShortcode::PREFIX . 'posts_grid') {
			if (isset($atts['template'])) {
				$atts['template'] = MPCEShortcodePostsGrid::fixTemplatePath($atts['template']);
			}
		}

		// Convert Row stretch classes to parameters
		if ($shortcodeName === MPCEShortcode::PREFIX . 'row' || $shortcodeName === MPCEShortcode::PREFIX . 'row_inner') {
			if (isset($atts['mp_style_classes']) && !empty($atts['mp_style_classes'])) {
				$mpStyleClassesArr = explode(' ', $atts['mp_style_classes']);
				if (($key = array_search('mp-row-fullwidth', $mpStyleClassesArr)) !== false){
					unset($mpStyleClassesArr[$key]);
					$atts['stretch'] = 'full';
					$atts['width_content'] = '';
				}
				$atts['mp_style_classes'] = implode(' ', $mpStyleClassesArr);
			}
		}
		
        $obj = $motopressCELibrary->getObject($shortcodeName);
        global $motopressCEWPAttachmentDetails;
        foreach($atts as $name => $value){
            if(key_exists($name, $obj->parameters)){
                if($obj->parameters[$name]['type'] === 'media' && isset($obj->parameters[$name]['returnMode']) && $obj->parameters[$name]['returnMode'] === 'id' && !empty($value) && !isset($motopressCEWPAttachmentDetails[$value])){
                    $url = wp_get_attachment_url($value);
                    if ($url) {
                        $motopressCEWPAttachmentDetails[$value] = $url;
                    }                                       
                } 
            }
        }
        $list= $motopressCELibrary->getObjectsList();

        $parameters = $list[ $shortcodeName ]['parameters'];

        $group = $list[$shortcodeName]['group'];

        //set parameters of shortcode
        if (!empty($parameters)) {
            foreach($parameters as $name => $param) {
                if (array_key_exists($name, $atts)) {
                    $value = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $atts[$name]);
                    $parameters[$name]['value'] = htmlentities($value, ENT_QUOTES, 'UTF-8');
                } else {
                    $parameters[$name] = new stdClass();
                }
            }
            $jsonParameters = (version_compare(PHP_VERSION, '5.4.0', '>=')) ? json_encode($parameters, JSON_UNESCAPED_UNICODE) : motopressCEJsonEncode($parameters);
            $parameters_str = " " . MPCEShortcode::$attributes['parameters'] . "='" . $jsonParameters . "'";
        }

        //set styles
        $styles = array();
        if (!empty(MPCEShortcode::$styles)) {
            foreach(MPCEShortcode::$styles as $name => $value) {
                if (array_key_exists($name, $atts)) {
                    $value = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $atts[$name]);
                    $styles[$name]['value'] = htmlentities($value, ENT_QUOTES, 'UTF-8');
                } else {
                    $styles[$name] = new stdClass();
                }
            }

            if (!is_array($styles['mp_style_classes'])) {
                if (array_key_exists($shortcodeName, $motopressCELibrary->deprecatedParameters)) {
                    foreach ($motopressCELibrary->deprecatedParameters[$shortcodeName] as $key => $val){
                        if (array_key_exists($key, $atts)){
                            if (!is_array($styles['mp_style_classes'])){
                                $styles['mp_style_classes'] = array();
                                $styles['mp_style_classes']['value'] = '';
                            }
                            if ($shortcodeName === MPCEShortcode::PREFIX . 'button') {
                                if ($key === 'color' && $atts[$key] === 'default') {
                                    $className = $val['prefix'] . 'silver';
                                } elseif ($key === 'size') {
                                    $className = ($atts[$key] === 'default') ? $val['prefix'] . 'middle' : $val['prefix'] . $atts[$key];
                                    $className .= ' motopress-btn-rounded';
                                } else {
                                    $className = $val['prefix'] . $atts[$key];
                                }
                            } else {
                                $className = $val['prefix'] . $atts[$key];
                            }
                            $styles['mp_style_classes']['value'] .=  $styles['mp_style_classes']['value'] === '' ? $className : ' ' . $className;
                        }
                    }
                }
            }

            $jsonStyles = (version_compare(PHP_VERSION, '5.4.0', '>=')) ? json_encode($styles, JSON_UNESCAPED_UNICODE) : motopressCEJsonEncode($styles);
            $styles_str = " " . MPCEShortcode::$attributes['styles'] . "='" . $jsonStyles . "'";
        }

        // set close-type of shortcode
        if (preg_match('/\[\/' . $shortcodeName .'\](?:<br \\/>)?(?:<\\/p>)?$/', $matches[0])===1){
            $endstr = '[/' . $shortcodeName .']';
            $closeType = MPCEObject::ENCLOSED;
        } else {
            $endstr = '';
            $closeType = MPCEObject::SELF_CLOSED;
        }

        //wrap custom code
        $cleanRegex = motopressCEGetMPShortcodeRegex();
        $wrapCustomCodeRegex = '/\A(?:' . $cleanRegex . ')+\Z/s';

        if (isset($grid['span']['type']) && $grid['span']['type'] === 'multiple') {
            $spanShortcodes = array_merge($grid['span']['shortcode'], $grid['span']['inner']);
        } else {
            $spanShortcodes = array($grid['span']['shortcode'], $grid['span']['inner']);
        }
        if (
            ($content !== '') &&
            ($content !== '&nbsp;') &&
            (in_array($shortcodeName, $spanShortcodes)) &&
            (!preg_match($wrapCustomCodeRegex, $content)) //$regex
        ) {
            $content = motopressCEWrapCustomCode($content);
        }

        // set system marking for "must-unwrap" code
        if ($shortcodeName == 'mp_code') {
            if (!empty($atts)) {
                if (isset($atts['unwrap']) && $atts['unwrap'] === 'true') {
                    $unwrap = ' ' . MPCEShortcode::$attributes['unwrap'] . ' = "true"';
                }
            }
        }

	    // Members Widget fix
	    if ($shortcodeName == 'mp_members_content') {
		    if (!empty($atts)) {
				if (isset($atts['members_content'])) {
					$content = $atts['members_content'];
					unset($atts['members_content']);
				}
		    }
	    }

		// Rebuild string of attributes
		$attsStr = '';
		foreach ($atts as $name => $value) {
			$attsStr .= ' ' . $name . '="' . $value . '"';
		}

        $dataContent = '';

        //setting data-motopress-content for all objects except layout
        if (isset($grid['span']['type']) && $grid['span']['type'] === 'multiple') {
            $gridShortcodes = array_merge(array($grid['row']['shortcode'],$grid['row']['inner']), $grid['span']['shortcode'], $grid['span']['inner']);
        } else {
            $gridShortcodes = array($grid['row']['shortcode'],$grid['row']['inner'],$grid['span']['shortcode'],$grid['span']['inner']);
        }
        if (!in_array($shortcodeName , $gridShortcodes)){
            $dataContent = motopressCEScreeningDataAttrShortcodes($content);
//	        $dataContent = motopressCERemoveMoreTag($dataContent);
        }	

		if (in_array($shortcodeName, $spanShortcodes) && isset($atts[$grid['span']['custom_class_attr']]) && preg_match('/\bmotopress-empty\b/', $atts[$grid['span']['custom_class_attr']])) {
			$content = '<div class="motopress-filler-content"></div>';
		}

        if (in_array($shortcodeName, $gridShortcodes) || in_array($shortcodeName, $groupObjects)) {
            return '<div '.MPCEShortcode::$attributes['closeType'].'="' . $closeType . '" '.MPCEShortcode::$attributes['shortcode'].'="' . $shortcodeName .'" '.MPCEShortcode::$attributes['group'].'="' . $group .'"' . $parameters_str . $styles_str . ' '.MPCEShortcode::$attributes['content'].'="' . htmlentities($dataContent, ENT_QUOTES, 'UTF-8') . '" ' . $unwrap . '>[' . $shortcodeName . $attsStr . ']' . preg_replace_callback($regex, 'motopressCEParseObjectsRecursive', $content) . $endstr . '</div>';
        } else {
            $content = MPCEShortcode::unautopMotopressShortcodes($content);
            return '<div '.MPCEShortcode::$attributes['closeType'].'="' . $closeType . '" '.MPCEShortcode::$attributes['shortcode'].'="' . $shortcodeName .'" '.MPCEShortcode::$attributes['group'].'="' . $group .'"' . $parameters_str . $styles_str . ' '.MPCEShortcode::$attributes['content'].'="' . htmlentities($dataContent, ENT_QUOTES, 'UTF-8') . '" ' . $unwrap . '>[' . $shortcodeName . $attsStr . ']' . $content . $endstr . '</div>';
        }
    }

    return preg_replace_callback($regex, 'motopressCEParseObjectsRecursive', $matches);
}

/**
 * Cut "more section" from the content and append it at the end.
 *
 * @param string $content
 * @return string
 */
function motopressCEMoreTagBubbling($content) {
	if (preg_match('/(<section class="mpce-wp-more-tag">.*?<\/section>)/', $content, $matches)) {
		$content = preg_replace('/<section class="mpce-wp-more-tag">.*?<\/section>/', '', $content);
		$content .= $matches[1];
	}
	return motopressCEClearEmptyRows($content);
}

function motopressCERemoveMoreTag($content) {
    return preg_replace('/<section class="mpce-wp-more-tag">.*?<\/section>/', '', $content);
}

function motopressCEClearEmptyRows( $content ){
    $motopressCELibrary = MPCELibrary::getInstance();
    $grid = $motopressCELibrary->getGridObjects();
    if (isset($grid['span']['type']) &&  $grid['span']['type'] === 'multiple') {
        $fullSpanShortcodeName = end($grid['span']['shortcode']);
        reset($grid['span']['shortcode']);
        $fullSpanShortcode = '\[' . $fullSpanShortcodeName .'\]';
        $fullSpanCloseShortcode = '\[\/'.$fullSpanShortcodeName.'\]';
    } else {
        $fullSpanShortcode = '\[' . $grid['span']['shortcode'].' '.$grid['span']['attr'].'="'.$grid['row']['col'] . '"\]';
        $fullSpanCloseShortcode = '\[\/'.$grid['span']['shortcode'].'\]';
    }
    return preg_replace('/(?:<p>)?\['.$grid['row']['shortcode'].'\]'  . '(?:<\\/p>)?(?:<p>)?' . $fullSpanShortcode . '(?:<\\/p>)?(?:<p>)?' . $fullSpanCloseShortcode . '(?:<\\/p>)?(?:<p>)?' . '\[\/'.$grid['row']['shortcode'].'\](?:<\\/p>)?(?:<p>)?/', '', $content);
}

function motopressCEWrapOuterCode($content) {
        $motopressCELibrary = MPCELibrary::getInstance();
        $grid = $motopressCELibrary->getGridObjects();
        $content = stripslashes( $content );
        if (isset($grid['span']['type']) && $grid['span']['type'] === 'multiple') {
            $fullSpanShortcodeName = end($grid['span']['shortcode']);
            reset($grid['span']['shortcode']);
            $fullSpanShortcode = '[' . $fullSpanShortcodeName .']';
            $fullSpanCloseShortcode = '[/'.$fullSpanShortcodeName.']';
        } else {
            $fullSpanShortcode = '['.$grid['span']['shortcode'].' '.$grid['span']['attr'].'="'.$grid['row']['col'].'"]';
            $fullSpanCloseShortcode = '[/'.$grid['span']['shortcode'].']';
        }
        if (!preg_match('/.*?\['.$grid['row']['shortcode'].'\s?.*\].*\[\/'.$grid['row']['shortcode'].'\].*/s', $content)){
            $content = '['.$grid['row']['shortcode'].']' . $fullSpanShortcode  . $content . $fullSpanCloseShortcode . '[/'.$grid['row']['shortcode'].']';
        }
        preg_match('/(\A.*?)((?:<p>)?\['.$grid['row']['shortcode'].'\s?.*\].*\[\/'.$grid['row']['shortcode'].'\](?:<\\/p>)?)(.*\Z)/s', $content, $matches);
        $result = '';
        $beforeContent = !empty($matches[1]) ? '['.$grid['row']['shortcode'].']' . $fullSpanShortcode . $matches[1] . $fullSpanCloseShortcode . '[/'.$grid['row']['shortcode'].']' :'';
        $result .= motopressCEMoreTagBubbling($beforeContent);
        $result .= $matches[2];
        $afterContent = !empty($matches[3]) ? '['.$grid['row']['shortcode'].']' . $fullSpanShortcode . $matches[3] . $fullSpanCloseShortcode . '[/'.$grid['row']['shortcode'].']' :'';
        $result .= motopressCEMoreTagBubbling($afterContent);

        return $result;
}

function motopressCEGetMPShortcodeRegex(){
    $motopressCELibrary = MPCELibrary::getInstance();

    $shortcodes = $motopressCELibrary->getObjectsNames();

    $tagnames = array_values($shortcodes);
    $tagregexp = join( '|', array_map('preg_quote', $tagnames) );
    // see wp_spaces_regexp() Since: WordPress 4.0.0
    $spaces = '[\r\n\t ]|\xC2\xA0|&nbsp;';

    $pattern  =
            '(?:<p>)?'                              // Opening paragraph
            . '(?:' . $spaces . ')*+'   // Optional leading whitespace
            . '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . '(' . $tagregexp . ')'                     // 2: Shortcode name
            . '\\b'                              // Word boundary
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            . '(?:<br \\/>)?'
//            .     '(?:<\\/p>)?'
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
//            .             '[^<]*+'             // Not an opening bracket
//            .             '(?:'
//            .                 '<(?!p>\\[\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
//            .                 '[^<]*+'         // Not an opening bracket
//            .             ')*+'
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
//            .     '(?:<p>)?'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)'                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
            . '(?:' . $spaces . ')*+'            // optional trailing whitespace
            . '(?:<br \\/>)?'
            . '(?:<\\/p>)?';                           // closing paragraph
    return $pattern;

    return $pattern;
}

/*
 * replacement of [ to [] for supression of incorect rendering
 */
function motopressCEScreeningDataAttrShortcodes($content){
    return htmlspecialchars_decode(preg_replace('/\[/', '[]', $content), ENT_QUOTES);
}

function motopressCEWrapCustomCode($content){
    return '[mp_code unwrap="true"]' . $content . '[/mp_code]';
}

/**
 * @deprecated 1.6.9
 * Create temporary post with motopress adapted content
 */
function motopressCECreateTemporaryPost($post_id, $content) {
    $post = get_post($post_id);
    $post->ID = '';
    $post->post_title = 'temporary';
    $post->post_content = '<div class="motopress-content-wrapper">' . $content . '</div>';
    $post->post_status = 'trash';

    $userRole = wp_get_current_user()->roles[0];
    $optionName = 'motopress_tmp_post_id_' . $userRole;
    $id = get_option($optionName);

    if ($id) {
        if (is_null(get_post($id))) {
            $id = wp_insert_post($post, false);
            update_option($optionName, $id);
        }
    } else {
        $id = wp_insert_post($post, false);
        add_option($optionName, $id);
    }

    $post->ID = (int) $id;

    global $wpdb;
    $wpdb->delete($wpdb->posts, array('post_parent' => $post->ID, 'post_type' => 'revision'), array('%d', '%s')); //@todo: remove in next version

    wp_update_post($post);
    wp_untrash_post($post->ID);
    motopressCEClonePostmeta($post_id, $post->ID);
    do_action('mp_post_meta', $post->ID, $post->post_type);
    do_action('mp_theme_fix', $post_id, $post->ID, $post->post_type);
    $pageTemplate = get_post_meta($post_id, '_wp_page_template', true);
    $pageTemplate = (!$pageTemplate or empty($pageTemplate)) ? 'default' : $pageTemplate;
    update_post_meta($post->ID, '_wp_page_template', $pageTemplate);

    return $post->ID;
}

/** @deprecated 1.6.9 */
function motopressCEClonePostmeta( $post_id_from, $post_id_to){
    motopressCEClearPostmeta($post_id_to);

    update_post_meta($post_id_to, 'motopress-ce-edited-post', $post_id_from);

    $all_post_meta = get_post_custom_keys($post_id_from);
    if (is_array($all_post_meta)){
        foreach( $all_post_meta as $post_meta_key){
            // fix of the issue with "Custom Permalinks" plugin http://atastypixel.com/blog/wordpress/plugins/custom-permalinks/
            if ($post_meta_key == "custom_permalink") continue;
            $values = get_post_custom_values($post_meta_key, $post_id_from);
            foreach ($values as $value){
                add_post_meta($post_id_to, $post_meta_key, maybe_unserialize($value));
            }
        }
    }
}

function motopressCEClearPostmeta( $post_id ) {

    $all_post_meta = get_post_custom_keys($post_id);

    if (is_array($all_post_meta)) {
        foreach( $all_post_meta as $post_meta_key){
            delete_post_meta($post_id, $post_meta_key);
        }
    }

}

function motopressCECleanupShortcode($content) {
    return strtr($content, array (
        '<p>[' => '[',
        '</p>[' => '[',
        ']<p>' => ']',
        ']</p>' => ']',
        ']<br />' => ']'
    ));
}

/**
 * @deprecated 1.6.9
 * Disable store revisions for tmpPost
 */
function motopressCEDisableRevisions($num, $post) {
    $tmpPostId = get_option('motopress_tmp_post_id_' . wp_get_current_user()->roles[0]);
    if ($tmpPostId && $post->ID == $tmpPostId) {
        $num = 0;
    }
    return $num;
}
