<?php

/*
 * Add New Buttons styles Brand Button, White Button,  
 * Brand Table, Brand Accordion, Read More Post Grid 
 * Change default classes
 */
add_action('mp_library', 'mp_emmet_extendStyleClasses', 11, 1);

function mp_emmet_extendStyleClasses($motopressCELibrary) {


    $theme_color_primary = get_option('theme_color_primary', '#27b399');
    $theme_color_primary_light = get_option('theme_color_primary_light', '#37c4aa');

    if (isset($motopressCELibrary)) {

// button
        $buttonObj = &$motopressCELibrary->getObject('mp_button');
        if ($buttonObj) {
            $styleClasses = &$buttonObj->getStyle('mp_style_classes');

            $styleClasses['predefined']['color']['values']['theme-white'] = array(
                'class' => 'mp-theme-button-white',
                'label' => __('Theme White', 'emmet-lite')
            );
            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'mp-theme-button-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['default'] = array(
                'mp-theme-button-brand',
                'motopress-btn-size-middle',
                'motopress-btn-rounded',
                'motopress-btn-icon-indent-middle'
            );
        }

// accordion
        $accordionObj = &$motopressCELibrary->getObject('mp_accordion');
        if ($accordionObj) {
            $styleClasses = &$accordionObj->getStyle('mp_style_classes');
            $styleClasses['predefined']["style"]["values"]['theme-brand'] = array(
                'class' => 'mp-theme-accordion-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['default'] = array(
                'mp-theme-accordion-brand'
            );
        }
// table
        $tableObj = &$motopressCELibrary->getObject('mp_table');
        if ($tableObj) {
            $styleClasses = &$tableObj->getStyle('mp_style_classes');
            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'mp-theme-table-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['predefined']['color']['values']['theme-silver'] = array(
                'class' => 'motopress-table-style-silver',
                'label' => __('Light', 'emmet-lite')
            );
            unset($styleClasses['predefined']["style"]["values"]['silver']);
            $styleClasses['default'] = array(
                'mp-theme-table-brand',
                'motopress-table-first-col-left'
            );
        }
// postGrid
        $postGridObj = &$motopressCELibrary->getObject('mp_posts_grid');
        if ($postGridObj) {
            $styleClasses = &$postGridObj->getStyle('mp_style_classes');

            $post_style = array(
                'label' => 'Style',
                'allowMultiple' => true,
                'values' => array(
                    'theme-read-more-button' => array(
                        'class' => 'theme-read-more-button',
                        'label' => __('Theme Read More Style', 'emmet-lite')
                    ),
                )
            );
            $styleClasses['predefined']['style'] = $post_style;

            $postGridObj->parameters['image_size']['default'] = 'custom';
            $postGridObj->parameters['image_custom_size']['default'] = '750x375';
            $postGridObj->parameters['title_tag']['default'] = 'h3';
            $postGridObj->parameters['filter_btn_color']['default'] = 'none';
        }
// list
        $listObj = &$motopressCELibrary->getObject('mp_list');
        if ($listObj) {
            $listObj->parameters['icon_color']['default'] = $theme_color_primary;
        }
// icon 
        $iconObj = &$motopressCELibrary->getObject('mp_icon');
        if ($iconObj) {
            $iconObj->parameters['icon_color']['default'] = '';
            $iconObj->parameters['bg_shape']['default'] = 'circle';
            $iconObj->parameters['icon_background_size']['default'] = 2;
            $iconObj->parameters['bg_color']['default'] = '';
            $styleClasses = &$iconObj->getStyle('mp_style_classes');
            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'mp-theme-icon-bg-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['default'] = array(
                'mp-theme-icon-bg-brand',
            );
        }
// button inner
        $buttonGroupInnerObj = &$motopressCELibrary->getObject('mp_button_inner');
        if ($buttonGroupInnerObj) {

            $buttonGroupInnerObj->parameters['color']['list']['mp-theme-button-brand'] = __('Theme Brand', 'emmet-lite');
            $buttonGroupInnerObj->parameters['color']['list']['mp-theme-button-white'] = __('Theme White', 'emmet-lite');
            $styleClasses = &$buttonGroupInnerObj->getStyle('mp_style_classes');

            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'mp-theme-button-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['predefined']['color']['values']['theme-white'] = array(
                'class' => 'mp-theme-button-white',
                'label' => __('Theme White', 'emmet-lite')
            );
            $buttonGroupInnerObj->parameters['color']['default'] = 'mp-theme-button-brand';
        }

// download button
        $downloadButtonObj = &$motopressCELibrary->getObject('mp_download_button');
        if ($downloadButtonObj) {
            $styleClasses = &$downloadButtonObj->getStyle('mp_style_classes');

            $styleClasses['predefined']['color']['values']['theme-white'] = array(
                'class' => 'mp-theme-button-white',
                'label' => __('Theme White', 'emmet-lite')
            );
            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'mp-theme-button-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['default'] = array(
                'mp-theme-button-brand',
                'motopress-btn-size-middle',
                'motopress-btn-rounded',
                'motopress-btn-icon-indent-middle'
            );
        }
// service box
        $serviceBoxObj = &$motopressCELibrary->getObject('mp_service_box');
        if ($serviceBoxObj) {
            $serviceBoxObj->parameters['heading_tag']['default'] = 'h4';
            $serviceBoxObj->parameters['icon_size']['default'] = 'large';
            $serviceBoxObj->parameters['icon_custom_color']['default'] = '#ffffff';
            $serviceBoxObj->parameters['icon_background_type']['default'] = 'circle';
            $serviceBoxObj->parameters['icon_background_size']['default'] = 2;
            $serviceBoxObj->parameters['icon_background_color']['default'] = '';
            $serviceBoxObj->parameters['button_custom_bg_color']['default'] = $theme_color_primary;
            $serviceBoxObj->parameters['button_custom_text_color']['default'] = '#ffffff';
            $serviceBoxObj->parameters['icon_color']['list']['mp-theme-icon-brand'] = __('Theme Brand', 'emmet-lite');
            $serviceBoxObj->parameters['icon_color']['default'] = 'mp-theme-icon-brand';
            $serviceBoxObj->parameters['button_color']['list']['mp-theme-button-brand'] = __('Theme Brand', 'emmet-lite');
            $serviceBoxObj->parameters['button_color']['default'] = 'mp-theme-button-brand';
            
            $styleClasses = &$serviceBoxObj->getStyle('mp_style_classes');
            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'motopress-service-box-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['default'] = array(
                'motopress-service-box-brand'
            );
        }
// call 
        $ctaObj = &$motopressCELibrary->getObject('mp_cta');
        if ($ctaObj) {
            $ctaObj->parameters['style_bg_color']['default'] = $theme_color_primary;
            $ctaObj->parameters['icon_color']['list']['mp-theme-icon-white'] = __('Theme Brand', 'emmet-lite');
            $ctaObj->parameters['icon_color']['default'] = 'mp-theme-icon-white';
            $ctaObj->parameters['style']['list']['brand'] = __('Theme Brand', 'emmet-lite');
            $ctaObj->parameters['style']['default'] = 'brand';
            $ctaObj->parameters['style_text_color']['default'] = '#ffffff';
            $ctaObj->parameters['button_color']['list']['mp-theme-button-white'] = __('Theme White', 'emmet-lite');
            $ctaObj->parameters['button_color']['default'] = 'mp-theme-button-white';
        }
// timer
        $countdownTimerObj = &$motopressCELibrary->getObject('mp_countdown_timer');
        if ($countdownTimerObj) {
            $countdownTimerObj->parameters['font_color']['default'] = '';
            $countdownTimerObj->parameters['block_color']['default'] = '';
            $styleClasses = &$countdownTimerObj->getStyle('mp_style_classes');
            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'mp-theme-countdown-timer-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['default'] = array(
                'mp-theme-countdown-timer-brand',
            );
        }
// chart
        $chartObj = &$motopressCELibrary->getObject('mp_google_chart');
        if ($chartObj) {
            $chartObj->parameters['colors']['default'] = $theme_color_primary . ',' . $theme_color_primary_light;
        }
// modal
        $modalObj = &$motopressCELibrary->getObject('mp_modal');
        if ($modalObj) {
            $styleClasses = &$modalObj->getStyle('mp_style_classes');

            $styleClasses['predefined']['color']['values']['theme-white'] = array(
                'class' => 'mp-theme-button-white',
                'label' => __('Theme White', 'emmet-lite')
            );
            $styleClasses['predefined']['color']['values']['theme-brand'] = array(
                'class' => 'mp-theme-button-brand',
                'label' => __('Theme Brand', 'emmet-lite')
            );
            $styleClasses['default'] = array(
                'mp-theme-button-brand',
                'motopress-btn-size-middle',
                'motopress-btn-rounded',
                'motopress-btn-icon-indent-middle'
            );
        }
    }
// tab
    $tabObj = &$motopressCELibrary->getObject('mp_tab');
    if ($tabObj) {
        $tabObj->parameters['icon_color']['list']['mp-theme-icon-brand'] = __('Theme Brand', 'emmet-lite');
        $tabObj->parameters['icon_color']['default'] = 'mp-theme-icon-brand';
    }
}
