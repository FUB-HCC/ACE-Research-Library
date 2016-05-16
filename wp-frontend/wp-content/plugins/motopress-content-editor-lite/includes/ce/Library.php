<?php
require_once dirname(__FILE__) . '/BaseElement.php';
require_once dirname(__FILE__) . '/Element.php';
require_once dirname(__FILE__) . '/Group.php';
require_once dirname(__FILE__) . '/Object.php';
require_once dirname(__FILE__) . '/Template.php';

/**
 * Description of MPCELibrary
 *
 */
class MPCELibrary {
    private $library = array();
    public $globalPredefinedClasses = array();
    public $tinyMCEStyleFormats = array();
    private $templates = array();
    private $gridObjects = array();
    public static $isAjaxRequest;
    private static $defaultGroup;
	private static $instance = null;
    public $deprecatedParameters = array(
        'mp_button' => array(
            'color' => array(
                'prefix' => 'motopress-btn-color-'
            ),
            'size' => array(
                'prefix' => 'motopress-btn-size-'
            )
        ),
        'mp_accordion' => array(
            'style' => array(
                'prefix' => 'motopress-accordion-'
            )
        ),
        'mp_social_buttons' => array(
            'size' => array(
                'prefix' => ''
            ),
            'style' => array(
                'prefix' => ''
            )
        ),
        'mp_table' => array(
            'style' => array(
                'prefix' => 'motopress-table-style-'
            )
        )
    );

	/**
	 * 
	 * @return MPCELibrary
	 */
	public static function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new MPCELibrary();
		}
		return self::$instance;
	}

    /**
     * @global stdClass $motopressCELang
     */
    private function __construct() {
        global $motopressCELang, $motopressCESettings;
        self::$isAjaxRequest = $this->isAjaxRequest();

/*
        $this->globalPredefinedClasses = array(
            'hidden' => array(
                'label' => $motopressCELang->CEHidden,
                'values' => array(
                    'phone' => array(
                        'class' => 'mp-hidden-phone',
                        'label' => $motopressCELang->CEHiddenPhone
                    ),
                    'tablet' => array(
                        'class' => 'mp-hidden-tablet',
                        'label' => $motopressCELang->CEHiddenTablet
                    ),
                    'desktop' => array(
                        'class' => 'mp-hidden-desktop',
                        'label' => $motopressCELang->CEHiddenDesktop
                    )
                )
            ),
            'align' => array(
                'label' => 'Align',
                'values' => array(
                    'left' => array(
                        'class' => 'motopress-text-align-left',
                        'label' => 'Left'
                    ),
                    'center' => array(
                        'class' => 'motopress-text-align-center',
                        'label' => 'Center'
                    ),
                    'right' => array(
                        'class' => 'motopress-text-align-right',
                        'label' => 'Right'
                    )
                )
            )
        );
*/

//        $padding = array(
//            'label' => 'Padding',
//            'values' => array(
//                'padding-5' => array(
//                    'class' => 'motopress-padding-5',
//                    'label' => 'Padding 5'
//                ),
//                'padding-10' => array(
//                    'class' => 'motopress-padding-10',
//                    'label' => 'Padding 10',
//                ),
//                'padding-15' => array(
//                    'class' => 'motopress-padding-15',
//                    'label' => 'Padding 15'
//                ),
//                'padding-25' => array(
//                    'class' => 'motopress-padding-25',
//                    'label' => 'Padding 25',
//                ),
//                'padding-50' => array(
//                    'class' => 'motopress-padding-50',
//                    'label' => 'Padding 50',
//                ),
//                'padding-75' => array(
//                    'class' => 'motopress-padding-75',
//                    'label' => 'Padding 75',
//                ),
//                'padding-100' => array(
//                    'class' => 'motopress-padding-100',
//                    'label' => 'Padding 100'
//                ),
//                'padding-150' => array(
//                    'class' => 'motopress-padding-150',
//                    'label' => 'Padding 150'
//                ),
//                'padding-200' => array(
//                    'class' => 'motopress-padding-200',
//                    'label' => 'Padding 200'
//                ),
//                'padding-250' => array(
//                    'class' => 'motopress-padding-250',
//                    'label' => 'Padding 250'
//                ),
//                'padding-300' => array(
//                    'class' => 'motopress-padding-300',
//                    'label' => 'Padding 300'
//                ),
//            )
//        );
//        $verticalPadding = array(
//            'label' => 'Vertical Padding',
//            'values' => array(
//                'vertical-padding-5' => array(
//                    'class' => 'motopress-vetical-padding-5',
//                    'label' => 'V.Padding 5',
//                ),
//                'vertical-padding-10' => array(
//                    'class' => 'motopress-vetical-padding-10',
//                    'label' => 'V.Padding 10',
//                ),
//                'vertical-padding-15' => array(
//                    'class' => 'motopress-vetical-padding-15',
//                    'label' => 'V.Padding 15',
//                ),
//                'vertical-padding-25' => array(
//                    'class' => 'motopress-vetical-padding-25',
//                    'label' => 'V.Padding 25',
//                ),
//                'vertical-padding-50' => array(
//                    'class' => 'motopress-vetical-padding-50',
//                    'label' => 'V.Padding 50',
//                ),
//                'vertical-padding-75' => array(
//                    'class' => 'motopress-vetical-padding-75',
//                    'label' => 'V.Padding 75',
//                ),
//                'vertical-padding-100' => array(
//                    'class' => 'motopress-vetical-padding-100',
//                    'label' => 'V.Padding 100'
//                ),
//                'vertical-padding-150' => array(
//                    'class' => 'motopress-vetical-padding-150',
//                    'label' => 'V.Padding 150'
//                ),
//                'vertical-padding-200' => array(
//                    'class' => 'motopress-vetical-padding-200',
//                    'label' => 'V.Padding 200'
//                ),
//                'vertical-padding-250' => array(
//                    'class' => 'motopress-vetical-padding-250',
//                    'label' => 'V.Padding 250'
//                ),
//                'vertical-padding-300' => array(
//                    'class' => 'motopress-vetical-padding-300',
//                    'label' => 'V.Padding 300'
//                ),
//            )
//        );

        $backgroundColor = array(
            'label' => 'Background Color',
            'values' => array(
                'blue' => array(
                    'class' => 'motopress-bg-color-blue',
                    'label' => 'Blue'
                ),
                'dark' => array(
                    'class' => 'motopress-bg-color-dark',
                    'label' => 'Dark'
                ),
                'gray' => array(
                    'class' => 'motopress-bg-color-gray',
                    'label' => 'Gray'
                ),
                'green' => array(
                    'class' => 'motopress-bg-color-green',
                    'label' => 'Green'
                ),
                'red' => array(
                    'class' => 'motopress-bg-color-red',
                    'label' => 'Red'
                ),
                'silver' => array(
                    'class' => 'motopress-bg-color-silver',
                    'label' => 'Silver'
                ),
                'white' => array(
                    'class' => 'motopress-bg-color-white',
                    'label' => 'White'
                ),
                'yellow' => array(
                    'class' => 'motopress-bg-color-yellow',
                    'label' => 'Yellow'
                )
            )
        );

        $style = array(
            'label' => 'Style',
            'allowMultiple' => true,
            'values' => array(
                'bg-alpha-75' => array(
                    'class' => 'motopress-bg-alpha-75',
                    'label' => 'Semi Transparent'
                ),
                'border' => array(
                    'class' => 'motopress-border',
                    'label' => 'Bordered'
                ),
                'border-radius' => array(
                    'class' => 'motopress-border-radius',
                    'label' => 'Rounded'
                ),
                'shadow' => array(
                    'class' => 'motopress-shadow',
                    'label' => 'Shadow'
                ),
                'shadow-bottom' => array(
                    'class' => 'motopress-shadow-bottom',
                    'label' => 'Bottom Shadow'
                ),
                'text-shadow' => array(
                    'class' => 'motopress-text-shadow',
                    'label' => 'Text Shadow'
                )
            )
        );

        $border = array(
            'label' => 'Border Side',
            'allowMultiple' => true,
            'values' => array(
                'border-top' => array(
                    'class' => 'motopress-border-top',
                    'label' => 'Border Top'
                ),
                'border-right' => array(
                    'class' => 'motopress-border-right',
                    'label' => 'Border Right'
                ),
                'border-bottom' => array(
                    'class' => 'motopress-border-bottom',
                    'label' => 'Border Bottom'
                ),
                'border-left' => array(
                    'class' => 'motopress-border-left',
                    'label' => 'Border Left'
                )
            )
        );

        $textColor = array(
            'label' => 'Text Color',
            'values' => array(
                'color-light' => array(
                    'class' => 'motopress-color-light',
                    'label' => 'Light Text'
                ),
                'color-dark' => array(
                    'class' => 'motopress-color-dark',
                    'label' => 'Dark Text'
                )
            )
        );

		$visiblePredefinedGroup = array(
			'label' => 'Visibility',
			'allowMultiple' => true,
			'values' => array(
				'hide-on-desktop' => array(
					'class' => 'motopress-hide-on-desktop',
					'label' => 'Hide On Desktop'
				),
				'hide-on-tablet' => array(
					'class' => 'motopress-hide-on-tablet',
					'label' => 'Hide On Tablet'
				),
				'hide-on-phone' => array(
					'class' => 'motopress-hide-on-phone',
					'label' => 'Hide On Phone'
				),
			)
		);

        $rowPredefinedStyles = array(
//            'padding' => $padding,
//            'vertical-padding' => $verticalPadding,
            'background-color' => $backgroundColor,
            'style' => $style,
            'border' => $border,
            'color' => $textColor,
			'visible' => $visiblePredefinedGroup
        );

        $spanPredefinedStyles = array(
//            'padding' => $padding,
//            'vertical-padding' => $verticalPadding,
            'background-color' => $backgroundColor,
            'style' => $style,
            'border' => $border,
            'color' => $textColor,
			'visible' => $visiblePredefinedGroup
        );

        $spacePredefinedStyles = array(
            'type' => array(
                'label' => 'Type',
                'values' => array(
                    'light' => array(
                        'class' => 'motopress-space-light',
                        'label' => 'Light'
                    ),
                    'normal' => array(
                        'class' => 'motopress-space-normal',
                        'label' => 'Normal'
                    ),
                    'dotted' => array(
                        'class' => 'motopress-space-dotted',
                        'label' => 'Dotted'
                    ),
                    'dashed' => array(
                        'class' => 'motopress-space-dashed',
                        'label' => 'Dashed'
                    ),
                    'double' => array(
                        'class' => 'motopress-space-double',
                        'label' => 'Double'
                    ),
                    'groove' => array(
                        'class' => 'motopress-space-groove',
                        'label' => 'Grouve'
                    ),
                    'ridge' => array(
                        'class' => 'motopress-space-ridge',
                        'label' => 'Ridge'
                    ),
                    'heavy' => array(
                        'class' => 'motopress-space-heavy',
                        'label' => 'Heavy'
                    )
                )
            )
        );
        /* Objects */
        //grid
        $rowParameters = array(
			'stretch' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEContainerWidth,
				'description' => strtr($motopressCELang->CEContainerWidthDesc, array('%link%' => admin_url('admin.php?page=motopress_options'))),
				'default' => '',
				'list' => array(
					'' => $motopressCELang->CEAuto,
					'full' => $motopressCELang->CEFullWidth,
					'fixed' => $motopressCELang->CEFixedWidth
				),
			),
			'width_content' => array(
				'type' => 'select',
				'label' => 'Content Width',
				'default' => '',
				'list' => array(
					'' => $motopressCELang->CEFixedWidth,
					'full' => $motopressCELang->CEFullWidth
				),
				'dependency' => array(
					'parameter' => 'stretch',
					'value' => 'full'
				)
			),
			'full_height' => array(
				'type' => 'checkbox',
				'label' => $motopressCELang->CEFullHeight,
				'default' => 'false'
			),
            'bg_media_type' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CERowObjTypeBGLabel,
                'description' => $motopressCELang->CERowObjTypeBGDesc,
//                'default' => 'disabled',
                'list' => array(
                    'disabled' => $motopressCELang->CERowObjTypeBGDisabled,
                    'video' => $motopressCELang->CERowObjTypeBGMP4,
                    'youtube' => $motopressCELang->CERowObjTypeBGYoutube,
                    'parallax' => $motopressCELang->CERowObjTypeBGParallax
                )
            ),
            'bg_video_youtube' => array(
                'type' => 'video',
                'label' => $motopressCELang->CERowObjBGYoutubeLabel,
                'default' => MPCEShortcode::DEFAULT_YOUTUBE_BG,
                'description' => $motopressCELang->CERowObjBGYoutubeDesc,
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_youtube_cover' => array(
                'type' => 'image',
                'label' => $motopressCELang->CERowObjBGVideoCoverImageLabel,
                'description' => $motopressCELang->CERowObjBGVideoCoverImageDesc,
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_youtube_repeat' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CERowObjBGVideoRepeatLabel,
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_youtube_mute' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CERowObjBGVideoMuteLabel,
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_webm' => array(
                'type' => 'media-video',
                'legend' => $motopressCELang->CERowObjBGVideoWEBMLegend,
                'label' => strtr($motopressCELang->CERowObjBGVideoFormatLabel, array('%name%' => 'WEBM')),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_mp4' => array(
                'type' => 'media-video',
                'label' => strtr($motopressCELang->CERowObjBGVideoFormatLabel, array('%name%' => 'MP4')),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_ogg' => array(
                'type' => 'media-video',
                'label' => strtr($motopressCELang->CERowObjBGVideoFormatLabel, array('%name%' => 'OGV')),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_cover' => array(
                'type' => 'image',
                'label' => $motopressCELang->CERowObjBGVideoCoverImageLabel,
                'description' => $motopressCELang->CERowObjBGVideoCoverImageDesc,
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_repeat' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CERowObjBGVideoRepeatLabel,
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_mute' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CERowObjBGVideoMuteLabel,
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'parallax_image' => array(
                'type' => 'image',
                'label' => $motopressCELang->CERowObjParallaxImageLabel,
                'description' => $motopressCELang->CERowObjParallaxImageDesc,
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'parallax'
                )
            ),
//            'parallax_speed' => array(
//                'type' => 'spinner',
//                'label' => '',
//                'description' => '',
//                'default' => 0.5,
//                'min' => -5,
//                'max' => 5,
//                'step' => 0.1,
//                'dependency' => array(
//                    'parameter' => 'bg_media_type',
//                    'value' => 'parallax'
//                )
//            ),
			'id' => array(
				'type' => 'text',
				'label' => $motopressCELang->CEID,
				'description' => $motopressCELang->CERowObjIdDesc
			)
        );
		
        $rowObj = new MPCEObject(MPCEShortcode::PREFIX . 'row', $motopressCELang->CERowObjName, null, $rowParameters, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
		$rowObjStyle = array(
			'mp_style_classes' => array(
				'predefined' => $rowPredefinedStyles,
				'additional_description' => $motopressCELang->CERowStyleClassesLabelAddtlDesc
			),
			'mp_custom_style' => array(
				'limitation' => 'margin-horizontal'
			)
		);
        $rowObj->addStyle($rowObjStyle);

        $rowInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'row_inner', $motopressCELang->CERowInnerObjName, null, $rowParameters, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
        $rowInnerObj->addStyle($rowObjStyle);

        $spanObj = new MPCEObject(MPCEShortcode::PREFIX . 'span', $motopressCELang->CESpanObjName, null, null, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
		$spanObjStyle = array(
			'mp_style_classes' => array(
				'predefined' => $spanPredefinedStyles
			),
			'mp_custom_style' => array(
				'limitation' => array('margin-horizontal')
			)
		);
        $spanObj->addStyle($spanObjStyle);

        $spanInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'span_inner', $motopressCELang->CESpanInnerObjName, null, null, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
        $spanInnerObj->addStyle($spanObjStyle);

		$this->setGrid(array(
            'row' => array(
                'shortcode' => 'mp_row',
                'inner' => 'mp_row_inner',
                'class' => 'mp-row-fluid',
                'edgeclass' => 'mp-row-fluid',
                'col' => '12'
            ),
            'span' => array(
                'type' => 'single',
                'shortcode' => 'mp_span',
                'inner' => 'mp_span_inner',
                'class' => 'mp-span',
                'attr' => 'col',
                'custom_class_attr' => 'classes'
            )
        ));

/* TEXT */
        $textObj = new MPCEObject(MPCEShortcode::PREFIX . 'text', $motopressCELang->CETextObjName, 'text.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => $motopressCELang->CETextObjButtonDesc . ' ' . $motopressCELang->CETextObjName,
                'text' => $motopressCELang->edit . ' ' . $motopressCELang->CETextObjName
            )
        ), 20, MPCEObject::ENCLOSED);
        $textPredefinedStyles = array();
        $this->extendPredefinedWithGoogleFonts($textPredefinedStyles);
        $textObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $textPredefinedStyles,
                'additional_description' => strtr($motopressCELang->CEGoogleFontsStyleClassesLabelAddtlDesc, array('%BrandName%' => $motopressCESettings['brand_name']))
            )
        ));

/* HEADER */
        $headingObj = new MPCEObject(MPCEShortcode::PREFIX . 'heading', $motopressCELang->CEHeadingObjName, 'heading.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => $motopressCELang->CETextObjButtonDesc . ' ' . $motopressCELang->CEHeadingObjName,
                'text' => $motopressCELang->edit . ' ' . $motopressCELang->CEHeadingObjName
            )
        ), 10, MPCEObject::ENCLOSED);
        $headingPredefinedStyles = array();
        $this->extendPredefinedWithGoogleFonts($headingPredefinedStyles);
        $headingObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $headingPredefinedStyles,
                'additional_description' => strtr($motopressCELang->CEGoogleFontsStyleClassesLabelAddtlDesc, array('%BrandName%' => $motopressCESettings['brand_name']))
            )
        ));

/* CODE */        
        $codeObj = new MPCEObject(MPCEShortcode::PREFIX . 'code', $motopressCELang->CECodeObjName, 'wordpress.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => $motopressCELang->CETextObjButtonDesc . ' ' . $motopressCELang->CECodeObjName,
                'text' => $motopressCELang->edit . ' ' . $motopressCELang->CECodeObjName
            )
        ), 30, MPCEObject::ENCLOSED);
        $codePredefinedStyles = array();
        $this->extendPredefinedWithGoogleFonts($codePredefinedStyles);
        $codeObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $codePredefinedStyles,
                'additional_description' => strtr($motopressCELang->CEGoogleFontsStyleClassesLabelAddtlDesc, array('%BrandName%' => $motopressCESettings['brand_name']))
            )
        ));

/* IMAGE */
        $imageObj = new MPCEObject(MPCEShortcode::PREFIX . 'image', $motopressCELang->CEImageObjName, 'image.png', array(
            'id' => array(
                'type' => 'image',
                'label' => $motopressCELang->CEImageObjSrcLabel,
                'default' => '',
                'description' => $motopressCELang->CEImageObjSrcDesc,
                'autoOpen' => 'true'
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjImageSizeLabel,
                'default' => 'full',
                'list' => array(
                    'full' => $motopressCELang->CEFull,
                    'large' => $motopressCELang->CELarge,
                    'medium' => $motopressCELang->CEMedium,
                    'thumbnail' => $motopressCELang->CEThumbnail,
                    'custom' => $motopressCELang->CECustom
                )
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => $motopressCELang->CEImageCustomSizeLabel,
                'dependency' => array(
                    'parameter' => 'size',
                    'value' => 'custom'
                ),
            ),
            'link_type' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEImageLinkLabel,
                'default' => 'custom_url',
                'list' => array(
                    'custom_url' => $motopressCELang->CECustomURL,
                    'media_file' => $motopressCELang->CEMediaFile,
                    'lightbox' => $motopressCELang->CELightbox
                )
            ),
            'link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEImageLinkLabel,
                'default' => '#',
                'description' => $motopressCELang->CEImageObjLinkDesc,
                'dependency' => array(
                    'parameter' => 'link_type',
                    'value' => 'custom_url'
                )
            ),
            'rel' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEImageRelLabel,
                'default' => '',
                'dependency' => array(
                    'parameter' => 'link_type',
                    'value' => 'media_file'
                )
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEOpenLinkInNewWindow,
                'default' => 'false'
            ),
            'caption' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEGalleryGridObjCaptionLabel,
                'description' => $motopressCELang->CEGalleryGridObjCaptionDesc,
                'default' => 'false'
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                )
            )
        ), 10);
        $imageObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-image-obj-basic',
                    'label' => 'Image'
                ),
                'selector' => 'img'
            ),
			'mp_custom_style' => array(
				'selector' => 'img',
				'limitation' => array(
					'margin'
				)
			)
        ));

/* GRID GALLERY */
        $gridGalleryObj = new MPCEObject(MPCEShortcode::PREFIX . 'grid_gallery', $motopressCELang->CEGridGalleryObjName,  'grid-gallery.png', array(
            'ids' => array(
                'type' => 'multi-images',
                'default' => '',
                'description' => $motopressCELang->CEMediaLibraryImagesIdsDesc,
                'text' => $motopressCELang->CEImageSliderObjIdsText,
                'autoOpen' => 'true'
            ),
            'columns' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEColumnsCount,
                'default' => 3,
                'list' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    6 => 6
                )
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjImageSizeLabel,
                'default' => 'thumbnail',
                'list' => array(
                    'full' => $motopressCELang->CEFull,
                    'large' => $motopressCELang->CELarge,
                    'medium' => $motopressCELang->CEMedium,
                    'thumbnail' => $motopressCELang->CEThumbnail,
                    'custom' => $motopressCELang->CECustom
                )
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => $motopressCELang->CEImageCustomSizeLabel,
                'dependency' => array(
                    'parameter' => 'size',
                    'value' => 'custom'
                ),
            ),
            'link_type' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEImageLinkLabel,
                'default' => 'lightbox',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'media_file' => $motopressCELang->CEMediaFile,
                    'attachment' => $motopressCELang->CEAttachmentPage,
                    'lightbox' => $motopressCELang->CELightbox,
                )
            ),
            'rel' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEImageRelLabel,
                'default' => '',
                'dependency' => array(
                    'parameter' => 'link_type',
                    'value' => 'media_file'
                )
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEOpenLinkInNewWindow,
                'default' => 'false',
            ),
            'caption' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEGalleryGridObjCaptionLabel,
                'description' => $motopressCELang->CEGalleryGridObjCaptionDesc,
                'default' => 'false',
            )
        ), 30);
        $gridGalleryObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-grid-gallery-obj-basic',
                    'label' => 'Grid Gallery'
                )
            )
        ));

/* POSTS SLIDER */
        $postsSliderObj = new MPCEObject(MPCEShortcode::PREFIX . 'posts_slider', $motopressCELang->CEPostsSliderObjName, 'post-slider.png', array(
            'post_type' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsSliderPostTypesLabel,
                'default' => 'post',
                'list' =>MPCEShortcode::getPostTypes(true), // true to get pages
            ),
            'category' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjCategoryLabel,
                'description' => $motopressCELang->CEPostsGridObjCategoryDesc,
				'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                ),
            ),
            'tag' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjTagLabel,
                'description' => $motopressCELang->CEPostsGridObjTagDesc,
				'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                ),
            ),
            'custom_tax' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjCustomTaxLabel,
                'default' => ''
            ),
            'custom_tax_field' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsGridObjCustomTaxFieldLabel,
                'default' => 'slug',
                'list' => array(
                    'term_id' => $motopressCELang->CETermID,
                    'slug' => $motopressCELang->CESlug,
                    'name' => $motopressCELang->CEName
                )
            ),
            'custom_tax_terms' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjCustomTaxTermLabel,
                'default' => '',
                'description' =>$motopressCELang->CEPostsGridObjCustomTaxTermDesc
            ),
            'posts_count' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEPostsSliderPostsCountLabel,
                'default' => 3,
				'min' => 1,
                'max' => 100,
                'step' => 1
            ),
            'order_by' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsSliderOrderBy,
                'default' => 'date',
                'list' => array(
                    'ID' => $motopressCELang->CEPostsSliderOrderByID,
                    'date' => $motopressCELang->CEPostsSliderOrderByDate,
                    'author' => $motopressCELang->CEPostsSliderOrderByAuthor,
                    'modified' => $motopressCELang->CEPostsSliderOrderByModified,
                    'rand' => $motopressCELang->CEPostsSliderOrderByRandom,
                    'comment_count' => $motopressCELang->CEPostsSliderOrderByCommentCount,
                    'menu_order' => $motopressCELang->CEPostsSliderOrderByMenuOrder,
                ),
            ),
            'sort_order' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjSortOrder,
                'default' => 'DESC',
                'list' => array(
                    'ASC' => $motopressCELang->CEPostsGridObjSortOrderAscending,
                    'DESC' => $motopressCELang->CEPostsGridObjSortOrderDescending
                ),
            ),
            'title_tag' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjPostTitleTag,
                'default' => 'h2',
                'list' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'hide' => $motopressCELang->CEPostsGridObjTitleTagNone,
                )
            ),
            'show_content' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjShowContent,
                'default' => 'short',
                'list' => array(
                    'short' => $motopressCELang->CEPostsGridObjShowContentShort,
                    'full' => $motopressCELang->CEPostsGridObjShowContentFull,
                    'excerpt' => $motopressCELang->CEPostsGridObjShowContentExcerpt,
                    'hide' => $motopressCELang->CEPostsGridObjShowContentNone,
                ),
            ),
            'short_content_length' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEPostsGridObjShortContentLength,
                'default' => 200,
                'min' => 0,
                'max' => 1000,
                'step' => 20,
                'dependency' => array(
                    'parameter' => 'show_content',
                    'value' => 'short'
                ),
            ),
            'image_size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjImageSizeLabel,
                'default' => 'thumbnail',
                'list' => array(
                    'full' => $motopressCELang->CEFull,
                    'large' => $motopressCELang->CELarge,
                    'medium' => $motopressCELang->CEMedium,
                    'thumbnail' => $motopressCELang->CEThumbnail,
                    'custom' => $motopressCELang->CECustom
                ),
				'dependency' => array(
					'parameter' => 'layout',
					'except' => 'title_text'
				)
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => $motopressCELang->CEImageCustomSizeLabel,
                'dependency' => array(
                    'parameter' => 'image_size',
                    'value' => 'custom'
                ),
            ),
            'layout' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEServiceBoxObjLayoutLabel,
                'default' => 'title_img_text_wrap',
                'list' => array(
                    //'title_img_text' => $motopressCELang->CEPostsSliderLayoutTitleImageText,
                    'img_title_text' => $motopressCELang->CEPostsSliderLayoutImageTitleText,
                    //'title_img_inline' => $motopressCELang->CEPostsSliderLayoutImageTitleInline,
                    'title_img_text_wrap' => $motopressCELang->CEPostsSliderLayoutImageTitleTextWrap,
					'title_text'=> $motopressCELang->CEPostsSliderLayoutTitleText
                ),
            ),
            'img_position' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsSliderImagePosition,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight,
                ),
				'dependency' => array(
					'parameter' => 'layout',
					'except' => 'title_text'
				)
            ),
            'post_link' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEImageLinkLabel,
                'default' => 'link_to_post',
                'list' => array(
                    'link_to_post' => $motopressCELang->CEPostsSliderPostLink,
                    'custom_link' => $motopressCELang->CEPostsSliderOpenCustomLinks,
                    'no_link' => $motopressCELang->CEPostsSliderNoLink,
                ),
            ),
            'custom_links' => array(
                'type' => 'longtext',
                'label' => $motopressCELang->CEPostsSliderOpenCustomLinks,
                'default' => site_url(),
                'description' => $motopressCELang->CEPostsSliderCustomLinksDescription,
                'dependency' => array(
                    'parameter' => 'post_link',
                    'value' => 'custom_link'
                ),
            ),
            'slideshow_speed' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsSliderAutoRotateLabel,
                'default' => '15000',
                'list' => array(
                    '3000' => '3',
                    '5000' => '5',
                    '10000' => '10',
                    '15000' => '15',
                    '25000' => '25',
                    'disable' => $motopressCELang->CEDisable,
                ),
            ),
            'animation' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEImageSliderObjAnimationLabel,
                'default' => 'fade',
                'list' => array(
                    'slide' => $motopressCELang->CEImageSliderObjAnimationSlide,
                    'fade' => $motopressCELang->CEImageSliderObjAnimationFade,
                ),
            ),
            'smooth_height' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjSmoothHeightLabel,
                'default' => 'true',
                'description' => $motopressCELang->CEImageSliderObjSmoothHeightDesc,
                'dependency' => array(
                    'parameter' => 'animation',
                    'value' => 'slide'
                )
            ),
            'show_nav' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjControlNavLabel,
                'default' => 'true',
            ),
            'pause_on_hover' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEPostsSliderPauseOnHoverLabel,
                'default' => 'true',
            ),
        ), 35);

/* IMAGE SLIDER */
        $imageSlider = new MPCEObject(MPCEShortcode::PREFIX . 'image_slider', $motopressCELang->CEImageSliderObjName, 'image-slider.png', array(
            'ids' => array(
                'type' => 'multi-images',
                'label' => $motopressCELang->CEImageSliderObjIdsLabel,
                'default' => '',
                'description' => $motopressCELang->CEMediaLibraryImagesIdsDesc,
                'text' => $motopressCELang->CEImageSliderObjIdsText,
                'autoOpen' => 'true'
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjImageSizeLabel,
                'default' => 'full',
                'list' => array(
                    'full' => $motopressCELang->CEFull,
                    'large' => $motopressCELang->CELarge,
                    'medium' => $motopressCELang->CEMedium,
                    'thumbnail' => $motopressCELang->CEThumbnail,
                    'custom' => $motopressCELang->CECustom
                )
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => $motopressCELang->CEImageCustomSizeLabel,
                'dependency' => array(
                    'parameter' => 'size',
                    'value' => 'custom'
                ),
            ),
            'animation' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEImageSliderObjAnimationLabel,
                'default' => 'fade',
                'description' => $motopressCELang->CEImageSliderObjAnimationDesc,
                'list' => array(
                    'fade' => $motopressCELang->CEImageSliderObjAnimationFade,
                    'slide' => $motopressCELang->CEImageSliderObjAnimationSlide
                )
            ),
            'smooth_height' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjSmoothHeightLabel,
                'default' => 'false',
                'description' => $motopressCELang->CEImageSliderObjSmoothHeightDesc,
                'dependency' => array(
                    'parameter' => 'animation',
                    'value' => 'slide'
                )
            ),
            'slideshow' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjAutoplayLabel,
                'default' => 'true',
                'description' => $motopressCELang->CEImageSliderObjAutoplayDesc
            ),
            'slideshow_speed' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEImageSliderObjSlideshowSpeedLabel,
                'default' => 7,
                'min' => 1,
                'max' => 20,
                'dependency' => array(
                    'parameter' => 'slideshow',
                    'value' => 'true'
                )
            ),
            'animation_speed' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEImageSliderObjAnimationSpeedLabel,
                'default' => 600,
                'min' => 200,
                'max' => 10000,
                'step' => 200
            ),
            'control_nav' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjControlNavLabel,
                'default' => 'true'
            )
        ), 20);
        $imageSlider->addStyle(array(
            'mp_style_classes' => array(
                'selector' => '> ul.slides'
            ),
//			'mp_custom_style' => array(
//				'selector' => '> ul.slides'
//			)
        ));

/* BUTTON */
	    $buttonParameters = array(
            'text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEButtonObjTextLabel,
                'default' => $motopressCELang->CEButtonObjName
            ),
            'link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEButtonObjLinkLabel,
                'default' => '#',
                'description' => $motopressCELang->CEButtonObjLinkDesc
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEOpenLinkInNewWindow,
                'default' => 'false'
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CEServiceBoxObjFontIconLabel,
                'default' => 'none',
                'list' => $this->getIconClassList(true)
            ),
            'icon_position' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEIconAlignment,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                ),
            ),
            'full_width' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEStretch,
                'default' => 'false'
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'full_width',
                    'value' => 'false'
                )
            ),
        );
		
        $buttonStyles = array(			
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-btn',
                    'label' => $motopressCELang->CEButtonObjBasicClassLabel
                ),
                'predefined' => array(
                    'color' => array(
                        'label' => $motopressCELang->CEButtonObjColorLabel,
                        'values' => array(
                            'silver' => array(
                                'class' => 'motopress-btn-color-silver',
                                'label' => $motopressCELang->CESilver
                            ),
                            'red' => array(
                                'class' => 'motopress-btn-color-red',
                                'label' => $motopressCELang->CERed
                            ),
                            'pink-dreams' => array(
                                'class' => 'motopress-btn-color-pink-dreams',
                                'label' => $motopressCELang->CEPinkDreams
                            ),
                            'warm' => array(
                                'class' => 'motopress-btn-color-warm',
                                'label' => $motopressCELang->CEWarm
                            ),
                            'hot-summer' => array(
                                'class' => 'motopress-btn-color-hot-summer',
                                'label' => $motopressCELang->CEHotSummer
                            ),
                            'olive-garden' => array(
                                'class' => 'motopress-btn-color-olive-garden',
                                'label' => $motopressCELang->CEOliveGarden
                            ),
                            'green-grass' => array(
                                'class' => 'motopress-btn-color-green-grass',
                                'label' => $motopressCELang->CEGreenGrass
                            ),
                            'skyline' => array(
                                'class' => 'motopress-btn-color-skyline',
                                'label' => $motopressCELang->CESkyline
                            ),
                            'aqua-blue' => array(
                                'class' => 'motopress-btn-color-aqua-blue',
                                'label' => $motopressCELang->CEAquaBlue
                            ),
                            'violet' => array(
                                'class' => 'motopress-btn-color-violet',
                                'label' => $motopressCELang->CEViolet
                            ),
                            'dark-grey' => array(
                                'class' => 'motopress-btn-color-dark-grey',
                                'label' => $motopressCELang->CEDarkGrey
                            ),
                            'black' => array(
                                'class' => 'motopress-btn-color-black',
                                'label' => $motopressCELang->CEBlack
                            )
                        )
                    ),
                    'size' => array(
                        'label' => $motopressCELang->CEObjSizeLabel,
                        'values' => array(
                            'mini' => array(
                                'class' => 'motopress-btn-size-mini',
                                'label' => $motopressCELang->CEMini
                            ),
                            'small' => array(
                                'class' => 'motopress-btn-size-small',
                                'label' => $motopressCELang->CESmall
                            ),
                            'middle' => array(
                                'class' => 'motopress-btn-size-middle',
                                'label' => $motopressCELang->CEMiddle
                            ),
                            'large' => array(
                                'class' => 'motopress-btn-size-large',
                                'label' => $motopressCELang->CELarge
                            )
                        )
                    ),
                    'icon indent' => array(
                        'label' => $motopressCELang->CEIconIndent,
                        'values' => array(
                            'mini' => array(
                                'class' => 'motopress-btn-icon-indent-mini',
                                'label' => $motopressCELang->CEMini . ' ' . $motopressCELang->CEIconIndent
                            ),
                            'small' => array(
                                'class' => 'motopress-btn-icon-indent-small',
	                            'label' => $motopressCELang->CESmall . ' ' . $motopressCELang->CEIconIndent
                            ),
                            'middle' => array(
                                'class' => 'motopress-btn-icon-indent-middle',
	                            'label' => $motopressCELang->CEMiddle . ' ' . $motopressCELang->CEIconIndent
                            ),
                            'large' => array(
                                'class' => 'motopress-btn-icon-indent-large',
	                            'label' => $motopressCELang->CELarge . ' ' . $motopressCELang->CEIconIndent
                            )
                        ),
                    ),
                    'rounded' => array(
                        'class' => 'motopress-btn-rounded',
                        'label' => $motopressCELang->CERounded
                    )
                ),
                'default' => array('motopress-btn-color-silver', 'motopress-btn-size-middle', 'motopress-btn-rounded', 'motopress-btn-icon-indent-small'),
                'selector' => '> a'
            ),
			'mp_custom_style' => array(
				'selector' => '> a'
			)
        );

        $buttonObj = new MPCEObject(MPCEShortcode::PREFIX . 'button', $motopressCELang->CEButtonObjName, 'button.png', $buttonParameters, 10);
        $buttonObj->addStyle($buttonStyles);

	    
	   
/* DOWNLOAD BUTTON*/        
        $downloadButtonObj = new MPCEObject(MPCEShortcode::PREFIX . 'download_button', $motopressCELang->CEDownloadButtonObjName, 'download-button.png', array(
			'attachment' => array(
                'type' => 'media',
                'returnMode' => 'id', // url or id
                'label' => $motopressCELang->CEMediaFile,
                'description' => $motopressCELang->CEMediaDescription,
                'default' => '',
            ),
			'text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEButtonObjTextLabel,
                'default' => 'Download'
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CEServiceBoxObjFontIconLabel,
                'default' => 'fa fa-download',
                'list' => $this->getIconClassList(true)
            ),
            'icon_position' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEIconAlignment,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
            ),
            'full_width' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEStretch,
                'default' => 'false'
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'full_width',
                    'value' => 'false'
                )
            )
		), 30);
		
	    $downloadButtonObj->addStyle($buttonStyles);


/* ICON */
        $iconObj = new MPCEObject(MPCEShortcode::PREFIX . 'icon', $motopressCELang->CEServiceBoxObjFontIconLabel, 'icon.png', array(
            'icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CEServiceBoxObjFontIconLabel,
                'default' => 'fa fa-star',
                'list' => $this->getIconClassList()
            ),
            'icon_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEServiceBoxObjIconColorLabel,
                'default' => '#e6cf03',
            ),
            'icon_size' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEObjSizeLabel,
                'default' => 'large',
                'list' => array(
                    'mini' => $motopressCELang->CEMini,
                    'small' => $motopressCELang->CESmall,
                    'middle' => $motopressCELang->CEMiddle,
                    'large' => $motopressCELang->CELarge,
                    'extra-large' => $motopressCELang->CEExtraLarge,
                    'custom' => $motopressCELang->CECustom,
                ),
            ),
            'icon_size_custom' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CEServiceBoxObjIconCustomSizeLabel,
		        'description' => $motopressCELang->CEServiceBoxObjIconCustomSizeDesc,
		        'min' => 1,
		        'step' => 1,
		        'max' => 500,
		        'default' => 26,
		        'dependency' => array(
			        'parameter' => 'icon_size',
			        'value' => 'custom'
		        )
	        ),   
            'icon_alignment' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEIconAlignment,
                'default' => 'center',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight,
                ),
            ),
            'bg_shape' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEIconBackgroundShape,
                'default' => 'none',
                'list' => array(
                    'none' => $motopressCELang->CEIconShapeNone,
                    'circle' => $motopressCELang->CEIconShapeCircle,
                    'square' => $motopressCELang->CEIconShapeSquare,
                    'rounded' => $motopressCELang->CEIconShapeRounded,
                    'outline-circle' => $motopressCELang->CEIconShapeOutlineCircle,
                    'outline-square' => $motopressCELang->CEIconShapeOutlineSquare,
                    'outline-rounded' => $motopressCELang->CEIconShapeOutlineRounded,
                ),
            ),
             'icon_background_size' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEServiceBoxObjIconBackgroundSize,
                'default' => 1.5,
                'min' => 1,
                'max' => 3,
                'step' => 0.1,
                'dependency' => array(
                    'parameter' => 'bg_shape',
                    'except' => 'none'
                )
            ),
            'bg_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEServiceBoxObjIconBackgroundColorLabel,
                'default' => '#42414f',
                'dependency' => array(
                    'parameter' => 'bg_shape',
                    'except' => 'none'
                ),
            ),
            'animation' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEIconAnimationLabel,
                'default' => 'none',
                'list' => array(
                    'none' => $motopressCELang->CEIconAnimationNone,
                    'top-to-bottom' => $motopressCELang->CEIconAnimationTopToBottom,
                    'bottom-to-top' => $motopressCELang->CEIconAnimationBottomToTop,
                    'left-to-right' => $motopressCELang->CEIconAnimationLeftToRight,
                    'right-to-left' => $motopressCELang->CEIconAnimationRightToLeft,
                    'appear' => $motopressCELang->CEIconAnimationAppear,
                ),
            ),
            'link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEIconObjLinkFieldLabel,
                'default' => ''
            ),
        ), 70);
		$iconObj->addStyle(array(
			'mp_custom_style' => array(
				'limitation' => 'padding'
			)
		));
           
/* COUNTDOWN TIMER */
       $countdownTimerObj = new MPCEObject(MPCEShortcode::PREFIX . 'countdown_timer', $motopressCELang->CECountdownTimerName, 'countdown-timer.png', array(

             'date' => array(
                'type' => 'datetime-picker',
                'displayMode' => 'datetime', // date | datetime (default)
                'returnMode' => 'YYYY-MM-DD H:m:s', // mysql format uses here (default: Y-m-d H:i:s )
                'label' => $motopressCELang->CEDatePickerLabel,
                'default' => '',
             ),
             'time_zone' => array(
                 'type' => 'select',
                 'label' => $motopressCELang->CECountdownTimeZone,
                'default' => 'server_time',
                'list' => array(
                    'server_time' => $motopressCELang->CEServerTime,
                    'user_local' => $motopressCELang->CEUserLocal
                ),
             ),
            'format' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEFormat,
                'default' => 'yowdHMS',
                'description' => $motopressCELang->CECountdownFormatDescription
            ),
            'block_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEBgColor,
                'default' => '#333333',
            ),
            'font_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CETextColor,
                'default' => '#ffffff',
            ),
            'blocks_size' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CECountdownBlocksSize,
		        'min' => 1,
		        'step' => 1,
		        'max' => 480,
		        'default' => 60,
	        ),
            'digits_font_size' => array(
		        'type' => 'slider',
		        'label' => $motopressCELang->CECountdownDigitsSize,
		        'min' => 8,
		        'step' => 1,
		        'max' => 300,
		        'default' => 36
	        ),
            'labels_font_size' => array(
		        'type' => 'slider',
		        'label' => $motopressCELang->CECountdownLabelsSize,
		        'min' => 6,
		        'step' => 1,
		        'max' => 96,
		        'default' => 12
	        ),
            'blocks_space' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CECountdownBlockSpace,
		        'min' => 0,
		        'step' => 1,
		        'max' => 160,
		        'default' => 10,
	        ),
         ), 70);
       

/* ACCORDION */
        $accordionObj = new MPCEObject(MPCEShortcode::PREFIX . 'accordion', $motopressCELang->CEAccordionObjName, 'accordion.png', array(
            'elements' => array(
                'type' => 'group',
                'contains' => MPCEShortcode::PREFIX . 'accordion_item',
                'items' => array(
                    'label' => array(
                        'default' => $motopressCELang->CEAccordionItemObjTitleLabel,
                        'parameter' => 'title'
                    ),
                    'count' => 2
                ),
                'text' => strtr($motopressCELang->CEAddNewItem, array('%name%' => $motopressCELang->CEAccordionObjName)),
                'activeParameter' => 'active',
                'rules' => array(
                    'rootSelector' => '.motopress-accordion-item',
                    'activeSelector' => '> h3',
                    'activeClass' => 'ui-state-active'
                ),
                'events' => array(
                    'onActive' => array(
                        'selector' => '> h3',
                        'event' => 'click'
                    ),
                    'onInactive' => array(
                        'selector' => '> h3',
                        'event' => 'click'
                    )
                )
            ),
        ), 25, MPCEObject::ENCLOSED);
        $accordionObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-accordion',
                    'label' => $motopressCELang->CEAccordionObjBasicClassLabel
                ),
                'predefined' => array(
                    'style' => array(
                        'label' => $motopressCELang->CEObjStyleLabel,
                        'values' => array(
                            'light' => array(
                                'class' => 'motopress-accordion-light',
                                'label' => $motopressCELang->CEAccordionObjStyleListLight
                            ),
                            'dark' => array(
                                'class' => 'motopress-accordion-dark',
                                'label' => $motopressCELang->CEAccordionObjStyleListDark
                            )
                        )
                    )
                ),
                'default' => array('motopress-accordion-light')
            )
        ));

        $accordionItemObj = new MPCEObject(MPCEShortcode::PREFIX . 'accordion_item', $motopressCELang->CEAccordionItemObjName, null, array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEAccordionItemObjTitleLabel,
                'default' => $motopressCELang->CEAccordionItemObjTitleLabel
            ),
            'content' => array(
                'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CEAccordionItemObjContentLabel,
                'default' => $motopressCELang->CEContentDefault,
                'text' => $motopressCELang->CEOpenInWPEditor,
                'saveInContent' => 'true'
            ),
            'active' => array(
                'type' => 'group-checkbox',
                'label' => $motopressCELang->CEActive,
                'default' => 'false',
                'description' => strtr($motopressCELang->CEActiveDesc, array('%name%' => $motopressCELang->CEAccordionItemObjName))
            )
        ), null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE, false);

/* TABS */
        $tabsObj = new MPCEObject(MPCEShortcode::PREFIX . 'tabs', $motopressCELang->CETabsObjName, 'tabs.png', array(
            'elements' => array(
                'type' => 'group',
                'contains' => MPCEShortcode::PREFIX . 'tab',
                'items' => array(
                    'label' => array(
                        'default' => $motopressCELang->CETabObjTitleLabel,
                        'parameter' => 'title'
                    ),
                    'count' => 2
                ),
                'text' => strtr($motopressCELang->CEAddNewItem, array('%name%' => $motopressCELang->CETabObjName)),
	            'activeParameter' => 'active',
                'rules' => array(
                    'rootSelector' => '.ui-tabs-nav > li',
                    'activeSelector' => '',
                    'activeClass' => 'ui-state-active'
                ),
                'events' => array(
                    'onActive' => array(
                        'selector' => '> a',
                        'event' => 'click'
                    )
                ),
            ),
            'padding' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CETabsObjPaddingLabel,
                'default' => 20,
                'min' => 0,
                'max' => 50,
                'step' => 10
            ),
            'vertical' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CETabsVertical,
                'default' => 'false'
            ),
            'rotate' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CETabsRotate,
                'default' => 'disable',
                'list' => array(
                    'disable' => $motopressCELang->CEDisable,
                    '3000' => '3',
                    '5000' => '5',
                    '10000' => '10',
                    '15000' => '15',
                )
            ),

       /*     'color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEColor,
                'default' => ''
            ),
            'spinner' => array(
                'type' => 'spinner',
                'label' => 'spinner',
                'description' => "desc <a href='http://google.ru' target='_blank'>link</a> <i>foo</i> <b>bar</b>",
                'default' => 50,
                'min' => 0,
                'max' => 100,
                'step' => 10
            ),
            'slider' => array(
                'type' => 'slider',
                'label' => 'Slider',
                'default' => 500,
                'description' => 'Description',
                'min' => -101,
                'max' => 999,
                'step' => 1
            ),
            'buttonsgroup' => array(
                'type' => 'radio-buttons',
                'label' => 'Toggle button group',
                'default' => '#00ff00',
                'list' => array(
                    '#ff0000' => 'Red',
                    '#00ff00' => 'Green',
                    '#0000ff' => 'Blue',
                    '#000000' => 'Black',
                    '#f32222' => 'Red 2',
                    '#22f322' => 'Green 2',
                    '#2222f3' => 'Blue 2',
                    '#cccccc' => 'Gray'
                )
            )

            'layout' => array(
                'type' => 'select',
                'label' => 'layout',
                'default' => 'top-left',
                'list' => array(
                    'top-left' => 'top left'
                )
            ),
            'color' => array(
                'type' => 'select',
                'label' => 'color',
                'default' => 'gray',
                'list' => array(
                    'left' => 'gray'
                )
            )
*/
        ), 20, MPCEObject::ENCLOSED);
        $tabsObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-tabs-basic',
                    'label' => $motopressCELang->CETabsObjBasicClassLabel
                ),
                'predefined' => array(
                    'style' => array(
                        'label' => $motopressCELang->CETabsObjStyleFullWidth,
                        'values' => array(
                            'full-width' => array(
                                'class' => 'motopress-tabs-fullwidth',
                                'label' => $motopressCELang->CETabObjStyleFullWidth
                            )
                        )
                    ),
                ),
                'selector' => ''
            )
        ));

        $tabObj = new MPCEObject(MPCEShortcode::PREFIX . 'tab', $motopressCELang->CETabObjName, null, array(
            'id' => array(
                'type' => 'text-hidden'
            ),
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CETabObjTitleLabel,
                'default' => $motopressCELang->CETabObjTitleLabel
            ),
            'content' => array(
                'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CETabObjContentLabel,
                'default' => $motopressCELang->CEContentDefault,
                'text' => $motopressCELang->CEOpenInWPEditor,
                'saveInContent' => 'true'
            ),
	        'icon' => array(
		        'type' => 'icon-picker',
		        'label' => $motopressCELang->CEServiceBoxObjFontIconLabel,
		        'default' => 'none',
		        'list' => $this->getIconClassList(true)
	        ),
	        'icon_size' => array(
		        'type' => 'radio-buttons',
		        'label' => $motopressCELang->CEServiceBoxObjIconSizeLabel,
		        'default' => 'normal',
		        'list' => array(
			        'normal' => $motopressCELang->CENormal,
			        'custom' => $motopressCELang->CECustom,
		        ),
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_custom_size' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CEServiceBoxObjIconCustomSizeLabel,
		        'description' => $motopressCELang->CEServiceBoxObjIconCustomSizeDesc,
		        'min' => 1,
		        'step' => 1,
		        'max' => 500,
		        'default' => 26,
		        'dependency' => array(
			        'parameter' => 'icon_size',
			        'value' => 'custom'
		        )
	        ),
	        'icon_color' => array(
		        'type' => 'color-select',
		        'label' => $motopressCELang->CEServiceBoxObjIconColorLabel,
		        'default' => 'inherit',
		        'list' => array(
					'inherit' => $motopressCELang->CEInherit,
			        'mp-text-color-black' => $motopressCELang->CEBlack,
			        'mp-text-color-red' => $motopressCELang->CERed,
			        'mp-text-color-pink-dreams' => $motopressCELang->CEPinkDreams,
			        'mp-text-color-warm' => $motopressCELang->CEWarm,
			        'mp-text-color-hot-summer' => $motopressCELang->CEHotSummer,
			        'mp-text-color-olive-garden' => $motopressCELang->CEOliveGarden,
			        'mp-text-color-green-grass' => $motopressCELang->CEGreenGrass,
			        'mp-text-color-skyline' => $motopressCELang->CESkyline,
			        'mp-text-color-aqua-blue' => $motopressCELang->CEAquaBlue,
			        'mp-text-color-violet' => $motopressCELang->CEViolet,
			        'mp-text-color-dark-grey' => $motopressCELang->CEDarkGrey,
			        'mp-text-color-default' => $motopressCELang->CESilver,
			        'custom' => $motopressCELang->CECustom,
		        ),
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_custom_color' => array(
		        'type' => 'color-picker',
		        'label' => $motopressCELang->CEServiceBoxObjIconCustomColorLabel,
		        'default' => '#000000',
		        'dependency' => array(
			        'parameter' => 'icon_color',
			        'value' => 'custom'
		        )
	        ),
	        'icon_margin_left' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CEIconMarginLeftLabel,
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '5',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_margin_right' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CEIconMarginRightLabel,
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '5',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_margin_top' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CEIconMarginTopLabel,
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '0',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_margin_bottom' => array(
		        'type' => 'spinner',
		        'label' => $motopressCELang->CEIconMarginBottomLabel,
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '0',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'active' => array(
		        'type' => 'group-checkbox',
		        'label' => $motopressCELang->CEActive,
		        'default' => 'false',
		        'description' => strtr($motopressCELang->CEActiveDesc, array('%name%' => $motopressCELang->CETabObjName))
	        )
        ), null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE, false);

/* SOCIAL BUTTONS */
        $socialsObj = new MPCEObject(MPCEShortcode::PREFIX . 'social_buttons', $motopressCELang->CESocialsObjName, 'social-buttons.png', array(
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'motopress-text-align-left',
                'list' => array(
                    'motopress-text-align-left' => $motopressCELang->CELeft,
                    'motopress-text-align-center' => $motopressCELang->CECenter,
                    'motopress-text-align-right' => $motopressCELang->CERight
                )
            )
        ), 40, MPCEObject::ENCLOSED);
        $socialsObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => array(
                    'size' => array(
                        'label' => $motopressCELang->CEObjSizeLabel,
                        'values' => array(
                            'normal' => array(
                                'class' => 'motopress-buttons-32x32',
                                'label' => $motopressCELang->CESocialsObjSizeNormal
                            ),
                            'large' => array(
                                'class' => 'motopress-buttons-64x64',
                                'label' => $motopressCELang->CESocialsObjSizeLarge
                            )
                        )
                    ),
                    'style' => array(
                        'label' => $motopressCELang->CEObjStyleLabel,
                        'values' => array(
                            'plain' => array(
                                'class' => 'motopress-buttons-square',
                                'label' => $motopressCELang->CESocialsObjStyleSquare
                            ),
                            'rounded' => array(
                                'class' => 'motopress-buttons-rounded',
                                'label' => $motopressCELang->CERounded
                            ),
                            'circular' => array(
                                'class' => 'motopress-buttons-circular',
                                'label' => $motopressCELang->CESocialsObjStyleCircular
                            ),
                            'volume' => array(
                                'class' => 'motopress-buttons-volume',
                                'label' => $motopressCELang->CESocialsObjStyleVolume
                            )
                        )
                    )
                ),
                'default' => array('motopress-buttons-32x32', 'motopress-buttons-square')
            )
        ));

/* SOCIAL PROFILE/LINKS */
        $socialProfileObj = new MPCEObject(MPCEShortcode::PREFIX . 'social_profile', $motopressCELang->CESocialProfileObjName, 'social-profile.png', array(
            'facebook' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'Facebook')),
                'default' => 'https://www.facebook.com/motopressapp'
            ),
            'google' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'Google+')),
                'default' => 'https://plus.google.com/+Getmotopress/posts'
            ),
            'twitter' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'Twitter')),
                'default' => 'https://twitter.com/motopressapp'
            ),
            'pinterest' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'Pinterest')),
                'default' => 'http://www.pinterest.com/motopress/'
            ),
            'linkedin' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'LinkedIn')),
            ),
            'flickr' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'Flickr')),
            ),
            'vk' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'VK')),
            ),
            'delicious' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'Delicious')),
            ),
            'youtube' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'YouTube')),
                'default' => 'https://www.youtube.com/channel/UCtkDYmIQ5Lv_z8KbjJ2lpFQ'
            ),
            'rss' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'RSS')),
                'default' => 'http://www.getmotopress.com/feed/'
            ),
            'instagram' => array(
                'type' => 'text',
                'label' => strtr($motopressCELang->CESocialProfileObjURLLabel, array('%name%' => 'Instagram')),
                'default' => ''
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                )
            )
        ), 50);
        $socialProfileObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => array(
                    'size' => array(
                        'label' => $motopressCELang->CEObjSizeLabel,
                        'values' => array(
                            'normal' => array(
                                'class' => 'motopress-buttons-32x32',
                                'label' => $motopressCELang->CESocialsObjSizeNormal
                            ),
                            'large' => array(
                                'class' => 'motopress-buttons-64x64',
                                'label' => $motopressCELang->CESocialsObjSizeLarge
                            )
                        )
                    ),
                    'style' => array(
                        'label' => $motopressCELang->CEObjStyleLabel,
                        'values' => array(
                            'plain' => array(
                                'class' => 'motopress-buttons-square',
                                'label' => $motopressCELang->CESocialsObjStyleSquare
                            ),
                            'rounded' => array(
                                'class' => 'motopress-buttons-rounded',
                                'label' => $motopressCELang->CERounded
                            ),
                            'circular' => array(
                                'class' => 'motopress-buttons-circular',
                                'label' => $motopressCELang->CESocialsObjStyleCircular
                            ),
                            'volume' => array(
                                'class' => 'motopress-buttons-volume',
                                'label' => $motopressCELang->CESocialsObjStyleVolume
                            )
                        )
                    )
                ),
                'default' => array('motopress-buttons-32x32', 'motopress-buttons-square')
            )
        ));


/* VIDEO */
        $videoObj = new MPCEObject(MPCEShortcode::PREFIX . 'video', $motopressCELang->CEVideoObjName, 'video.png', array(
            'src' => array(
                'type' => 'video',
                'label' => $motopressCELang->CEVideoObjSrcLabel,
                'default' => MPCEShortcode::DEFAULT_VIDEO,
                'description' => $motopressCELang->CEVideoObjSrcDesc
            )
        ), 10);
        $videoObj->addStyle(array(
            'mp_style_classes' => array(
                'selector' => '> iframe'
            ),
			'mp_custom_style' => array(
				'selector' => '> iframe',
				'limitation' => array(
					'margin'
				)
			)
        ));

/* AUDIO */
         $wpAudioObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_audio', $motopressCELang->CEwpAudio, 'player.png', array(
            'source' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpAudioSourceTitle,
                'description' => $motopressCELang->CEwpAudioSourceDesc,
                'list' => array(
                    'library' => $motopressCELang->CEwpAudioSourceLibrary,
                    'external' => $motopressCELang->CEwpAudioSourceURL,
                ),
                'default' => 'external'
            ),
            'id' => array(
                'type' => 'audio',
                'label' => $motopressCELang->CEwpAudioIdTitle,
                'description' => $motopressCELang->CEwpAudioIdDescription,
                'default' => '',
                'dependency' => array(
                    'parameter' => 'source',
                    'value' => 'library'
                )
                ),
            'url' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpAudioUrlTitle,
                'description' => $motopressCELang->CEwpAudioUrlDescription,
                'default' => 'http://wpcom.files.wordpress.com/2007/01/mattmullenweg-interview.mp3',
                'dependency' => array(
                    'parameter' => 'source',
                    'value' => 'external'
                )
            ),
            'autoplay' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpAudioAutoplayTitle,
                'description' => $motopressCELang->CEwpAudioAutoplayDesc,
                'default' => '',
            ),
            'loop' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpAudioLoopTitle,
                'description' => $motopressCELang->CEwpAudioLoopDesc,
                'default' => '',
            )
        ), 20, MPCEObject::ENCLOSED);

/* GOOGLE MAPS */
        $gMapObj = new MPCEObject(MPCEShortcode::PREFIX.'gmap', $motopressCELang->CEGoogleMapObjName, 'map.png', array(
            'address' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEGoogleMapObjAddressLabel,
                'default' => 'Sydney, New South Wales, Australia',
                'description' => $motopressCELang->CEGoogleMapObjAddressDesc
            ),
            'zoom' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEGoogleMapObjZoomLabel,
                'default' => 13,
                'min' => 0,
                'max' => 20
            )
        ), 65, null, MPCEObject::RESIZE_ALL);
        $gMapObj->addStyle(array(
            'mp_style_classes' => array(
                'selector' => '> iframe'
            ),
			'mp_custom_style' => array(
				'selector' => '> iframe'
			)
        ));

/* SPACE */
        $spaceObj = new MPCEObject(MPCEShortcode::PREFIX . 'space', $motopressCELang->CESpaceObjName, 'space.png', null, 60, null, MPCEObject::RESIZE_ALL);
        $spaceObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $spacePredefinedStyles
            ),
			'mp_custom_style' => array(
				'limitation' => array('background', 'border', 'padding', 'margin-horizontal', 'color')
			)
        ));

/* EMBED */
        $embedObj = new MPCEObject(MPCEShortcode::PREFIX . 'embed', $motopressCELang->CEEmbedObjName, 'code.png', array(
            'data' => array(
                'type' => 'longtext64',
                'label' => $motopressCELang->CEEmbedObjPasteCode,
                'default' => 'PGk+UGFzdGUgeW91ciBjb2RlIGhlcmUuPC9pPg==',
                'description' => $motopressCELang->CEEmbedObjPasteCodeDescription
            ),
            'fill_space' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEEmbedObjFill,
                'default' => 'true',
                'description' => $motopressCELang->CEEmbedObjFillDescription
            )
        ), 75);

/* QUOTE */
        $quotesObj = new MPCEObject(MPCEShortcode::PREFIX . 'quote', $motopressCELang->CEQuotesObjName, 'quotes.png', array(
            'cite' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEQuotesObjCiteLabel,
                'default' => 'John Smith',
                'description' => $motopressCELang->CEQuotesObjCiteDesc,
            ),
            'cite_url' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEQuotesObjUrlLabel,
                'default' => '#',
                'description' => $motopressCELang->CEQuotesObjUrlDesc,
            ),
            'quote_content' => array(
                'type' => 'longtext',
                'label' => $motopressCELang->CEQuotesObjContentLabel,
                'default' => 'Lorem ipsum dolor sit amet.'
            )
        ), 40, MPCEObject::ENCLOSED);

/* MEMBERS CONTENT */
        $membersObj = new MPCEObject(MPCEShortcode::PREFIX . 'members_content', $motopressCELang->CEMembersObjName, 'members.png', array(
            'message' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEMembersObjMessageLabel,
                'default' => $motopressCELang->CEMembersObjMessageDefault,
                'description' => $motopressCELang->CEMembersObjMessageDesc,
            ),
            'login_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEMembersObjLoginTextLabel,
                'default' => $motopressCELang->CEMembersObjLoginTextDefault,
                'description' => $motopressCELang->CEMembersObjLoginTextDesc,
            ),
            'members_content' => array(
                'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CEMembersObjContentLabel,
                'default' => $motopressCELang->CEMembersObjContentValue,
	            'text' => $motopressCELang->CEOpenInWPEditor,
	            'saveInContent' => 'true'
            ),
        ), 50, MPCEObject::ENCLOSED);

/* CHARTS */
        $googleChartsObj = new MPCEObject(MPCEShortcode::PREFIX . 'google_chart', $motopressCELang->CEGoogleChartsObjName, 'chart.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEObjTitleLabel,
                'default' => 'Company Performance'
            ),
            'type' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEGoogleChartsObjTypeLabel,
                'description' => $motopressCELang->CEGoogleChartsObjTypeDesc,
                'default' => 'ColumnChart',
                'list' => array(
                    'ColumnChart' => $motopressCELang->CEGoogleChartsObjTypeListColumn,
                    'BarChart' => $motopressCELang->CEGoogleChartsObjTypeListBar,
                    'AreaChart' => $motopressCELang->CEGoogleChartsObjTypeListArea,
                    'SteppedAreaChart' => $motopressCELang->CEGoogleChartsObjTypeListStepped,
                    'PieChart' => $motopressCELang->CEGoogleChartsObjTypeListPie,
                    'PieChart3D' => $motopressCELang->CEGoogleChartsObjTypeList3D,
                    'LineChart' => $motopressCELang->CEGoogleChartsObjTypeListLine,
                    'Histogram' => $motopressCELang->CEGoogleChartsObjTypeListHistogram
                )
            ),
            'donut' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEGoogleChartsObjDonutLabel,
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' =>'PieChart'
                )
            ),
            'colors' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEGoogleChartsObjColorsLabel,
                'description' => $motopressCELang->CEGoogleChartsObjColorsDesc,
            ),
            'transparency' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEGoogleChartsObjTransparencyLabel,
                'default' => 'false',
            ),
            'table' => array(
                'type' => 'longtext-table',
                'label' => $motopressCELang->CEObjTableDataLabel,
                'description' => $motopressCELang->CEGoogleChartsObjDataDesc,
                'default' => 'Year,Sales,Expenses<br />2004,1000,400<br />2005,1170,460<br />2006,660,1120<br />2007,1030,540',
                'saveInContent' => 'true'
            )
        ), 80, MPCEObject::ENCLOSED, MPCEObject::RESIZE_ALL);

/* TABLE */
        $tableObj = new MPCEObject(MPCEShortcode::PREFIX . 'table', $motopressCELang->CETableObjName, 'table.png', array(
            'table' => array(
                'type' => 'longtext-table',
                'label' => $motopressCELang->CEObjTableDataLabel,
                'default' => 'Year,Sales,Expenses<br />2004,1000,400<br />2005,1170,460<br />2006,660,1120<br />2007,1030,540',
                'description' => $motopressCELang->CEObjTableDataDesc,
                'saveInContent' => 'true'
            )
        ), 30, MPCEObject::ENCLOSED);
        $tableObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-table',
                    'label' => $motopressCELang->CETableObjBasicClassLabel
                ),
                'predefined' => array(
                    'style' => array(
                        'label' => $motopressCELang->CEObjStyleLabel,
                        'allowMultiple' => true,
                        'values' => array(
                            'silver' => array(
                                'class' => 'motopress-table-style-silver',
                                'label' => $motopressCELang->CETableObjListLight
                            ),
                            'left' => array(
                                'class' => 'motopress-table-first-col-left',
                                'label' => $motopressCELang->CETableObjFirstColLeft
                            )
                        )
                    )
                ),
                'default' => array('motopress-table-style-silver', 'motopress-table-first-col-left'),
                'selector' => '> table'
            ),
			'mp_custom_style' => array(
				'selector' => '> table',
				'limitation' => array('padding')
			)
        ));

/* POSTS GRID */
        $postsGridObj = new MPCEObject(MPCEShortcode::PREFIX . 'posts_grid', $motopressCELang->CEPostsGridObjName, 'posts-grid.png', array(
            'query_type' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjQueryTypeLabel,
                'description' => $motopressCELang->CEPostsGridObjQueryTypeDesc,
                'default' => 'simple',
                'list' => array(
                    'simple' => $motopressCELang->CESimple,
                    'custom' => $motopressCELang->CEPostsGridObjCustomQueryLabel,
                    'ids' => $motopressCELang->CEIDs,
                )
            ),
            'post_type' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsGridObjPostTypeLabel,
                'description' => $motopressCELang->CEPostsGridObjPostTypeDesc,
                'list' =>MPCEShortcode::getPostTypes(false),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'category' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjCategoryLabel,
                'description' => $motopressCELang->CEPostsGridObjCategoryDesc,
                'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                )
            ),
            'tag' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjTagLabel,
                'description' => $motopressCELang->CEPostsGridObjTagDesc,
                'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                )
            ),
            'custom_tax' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjCustomTaxLabel,
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'custom_tax_field' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsGridObjCustomTaxFieldLabel,
                'default' => 'slug',
                'list' => array(
                    'term_id' => $motopressCELang->CETermID,
                    'slug' => $motopressCELang->CESlug,
                    'name' => $motopressCELang->CEName
                ),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'custom_tax_terms' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjCustomTaxTermLabel,
                'description' => $motopressCELang->CEPostsGridObjCustomTaxTermDesc,
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'posts_per_page' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEPostsGridObjPostsPerPageLabel,
                'default' => 3, // For backward compatibility in lite version must be 3 posts per page
                'min' => 1,
                'max' => 40,
                'step' => 1,
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
//			'show_sticky_posts' => array(
//				'type' => 'checkbox',
//				'label' => 'Show sticky posts',
//				'default' => 'false',
//				'dependency' => array(
//					'parameter' => 'query_type',
//					'value' => 'simple'
//				)
//			), 
            'posts_order' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjSortOrder,
                'default' => 'DESC',
                'list' => array(
                    'ASC' => $motopressCELang->CEPostsGridObjSortOrderAscending,
                    'DESC' => $motopressCELang->CEPostsGridObjSortOrderDescending
                ),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'custom_query' => array(
                'type' => 'longtext64',
                'label' => $motopressCELang->CEPostsGridObjCustomQueryLabel,
                'description' => $motopressCELang->CEPostsGridObjCustomQueryDesc,
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'custom'
                )
            ),
            'ids' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjIdsLabel,
                'description' => $motopressCELang->CEPostsGridObjIdsDesc,
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'ids'
                )
            ),
            'columns' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEColumnsCount,
                'default' => 2,
                'list' => array( 
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    6 => 6
                )
            ),
            'template' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsGridObjTemplateLabel,
                'list' => MPCEShortcode::getPostsGridTemplatesList(),
            ),
            'posts_gap' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEPostsGridObjPostsGapLabel,
                'default' => 30,
                'min' => 0,
                'max' => 100,
                'step' => 10,
            ),
            'show_featured_image' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEPostsGridObjShowFeaturedImage,
                'default' => 'true',
            ),
            'image_size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjImageSizeLabel,
                'default' => 'large',
                'list' => array(
                    'full' => $motopressCELang->CEFull,
                    'large' => $motopressCELang->CELarge,
                    'medium' => $motopressCELang->CEMedium,
                    'thumbnail' => $motopressCELang->CEThumbnail,
                    'custom' => $motopressCELang->CECustom
                ),
                'dependency' => array(
                    'parameter' => 'show_featured_image',
                    'value' => 'true'
                ),
            ),
            'image_custom_size' => array(
                'type' => 'text',
                'description' => $motopressCELang->CEImageCustomSizeLabel,
                'dependency' => array(
                    'parameter' => 'image_size',
                    'value' => 'custom'
                ),
            ),
            'title_tag' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjTitleTag,
                'default' => 'h2',
                'list' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'hide' => $motopressCELang->CEPostsGridObjTitleTagNone,
                )
            ),
            'show_date_comments' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEPostsGridObjShowDateComments,
                'default' => 'true',
            ),
            'show_content' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjShowContent,
                'default' => 'short',
                'list' => array(
                    'short' => $motopressCELang->CEPostsGridObjShowContentShort,
                    'full' => $motopressCELang->CEPostsGridObjShowContentFull,
                    'excerpt' => $motopressCELang->CEPostsGridObjShowContentExcerpt,
                    'hide' => $motopressCELang->CEPostsGridObjShowContentNone,
                )
            ),
            'short_content_length' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEPostsGridObjShortContentLength,
                'default' => 200,
                'min' => 0,
                'max' => 1000,
                'step' => 20,
                'dependency' => array(
                    'parameter' => 'show_content',
                    'value' => 'short'
                ),
            ),
            'read_more_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjReadMoreTextLabel,
                'default' => $motopressCELang->CEPostsGridObjReadMoreText
            ),
            'display_style' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjDisplayStyle,
                'default' => 'show_all',
                'list' => array(
                    'show_all' => $motopressCELang->CEShowAll,
                    'load_more' => $motopressCELang->CEPostsGridObjDisplayStyleLoadMore,
                    'pagination' => $motopressCELang->CEPostsGridObjDisplayStylePagination
                )
            ),
            'load_more_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjLoadMoreTextLabel,
                'default' => $motopressCELang->CEPostsGridObjLoadMoreTextDefault, // "Load More"
                'dependency' => array(
                    'parameter' => 'display_style',
                    'value' => 'load_more'
                )
            ),
            'filter' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjFilterLabel,
                'description' => $motopressCELang->CEPostsGridObjFilterDesc,
                'default' => 'none',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'cats' => $motopressCELang->CEPostsGridObjFilterByFirstTax,
                    'tags' => $motopressCELang->CEPostsGridObjFilterBySecondTax,
                    'both' => $motopressCELang->CEPostsGridObjFilterByBoth
                ),
				'dependency' => array(
					'parameter' => 'query_type',
					'value' => 'simple'
				)
			),
			'filter_tax_1' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEPostsGridObjFilterFirstTaxName,
				'description' => '',
				'default' => 'category',
				'list' => MPCEShortcode::getTaxonomiesList('category', false),
				'dependency' => array(
					'parameter' => 'filter',
					'value' => array( 'cats', 'both' )
				)
			),
			'filter_tax_2' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEPostsGridObjFilterSecondTaxName,
				'description' => '',
				'default' => 'post_tag',
				'list' => MPCEShortcode::getTaxonomiesList('post_tag', false),
				'dependency' => array(
					'parameter' => 'filter',
					'value' => array( 'tags', 'both' )
				)
			),
            'filter_btn_color' => array(
                'type' => 'color-select',
                'label' => $motopressCELang->CEButtonObjColorLabel,
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'motopress-btn-color-silver' => $motopressCELang->CESilver,
                    'motopress-btn-color-red' => $motopressCELang->CERed,
                    'motopress-btn-color-pink-dreams' => $motopressCELang->CEPinkDreams,
                    'motopress-btn-color-warm' => $motopressCELang->CEWarm,
                    'motopress-btn-color-hot-summer' => $motopressCELang->CEHotSummer,
                    'motopress-btn-color-olive-garden' => $motopressCELang->CEOliveGarden,
                    'motopress-btn-color-green-grass' => $motopressCELang->CEGreenGrass,
                    'motopress-btn-color-skyline' => $motopressCELang->CESkyline,
                    'motopress-btn-color-aqua-blue' => $motopressCELang->CEAquaBlue,
                    'motopress-btn-color-violet' => $motopressCELang->CEViolet,
                    'motopress-btn-color-dark-grey' => $motopressCELang->CEDarkGrey,
                    'motopress-btn-color-black' => $motopressCELang->CEBlack
                ),
                'dependency' => array(
                    'parameter' => 'filter',
                    'except' => 'none'
                )
            ),
			'filter_btn_divider' => array(
				'type' => 'text',
				'label' => $motopressCELang->CEFilterLinksDivider,
				'default' => '/',
				'dependency' => array(
					'parameter' => 'filter_btn_color',
					'value' => 'none'
				)
			),
            'filter_cats_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjFilterFirstTaxTextLabel,
                'default' => __('Categories') . ':',
                'dependency' => array(
                    'parameter' => 'filter',
                    'value' => array('cats', 'both')
                )
            ),
            'filter_tags_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjFilterSecondTaxTextLabel,
                'default' => __('Tags') . ':',
                'dependency' => array(
                    'parameter' => 'filter',
                    'value' => array('tags', 'both')
                )
            ),
            'filter_all_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjFilterViewAllTextLabel,
                'default' => __('All'),
                'dependency' => array(
                    'parameter' => 'filter',
                    'except' => 'none'
                )
            ),
//            'widget_id' => array(
//                'type' => 'text-hidden',
//                'default' => uniqid()
//            )
        ), 10);
        $postsGridObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-posts-grid-basic',
                    'label' => $motopressCELang->CEPostsGridObjBasicClassLabel
                )
            )
        ));

/* MODAL */
        $modalObj = new MPCEObject(MPCEShortcode::PREFIX . 'modal', $motopressCELang->CEModalObjName, "modal.png", array(
            'content' => array(
				'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CEObjContent,
				'text' => $motopressCELang->edit . ' ' . $motopressCELang->CEModalObjName,
                'default' => $motopressCELang->CEContentWithTagsDefault,
				'saveInContent' => 'true'
            ),
			'modal_style' => array(
				'type' => 'radio-buttons',
				'label' => $motopressCELang->CEObjStyle,
				'default' => 'dark',
				'list' => array(
					'dark' => $motopressCELang->CEDark,
					'light' => $motopressCELang->CELight,
					'custom' => $motopressCELang->CECustom
				)
			),
			'modal_shadow_color' => array(
				'type' => 'color-picker',
                'label' => $motopressCELang->CEObjShadowColorLabel,
                'default' => '#0b0b0b',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'modal_content_color' => array(
				'type' => 'color-picker',
                'label' => $motopressCELang->CEObjContentColorLabel,
                'default' => '#ffffff',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'button_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEButtonObjTextLabel,
                'default' => 'Open Modal Box'
            ),
            'button_full_width' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEStretch . ' ' . $motopressCELang->CEButtonObjName,
                'default' => 'false'
            ),
            'button_align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEButtonObjName . ' ' . $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'button_full_width',
                    'value' => 'false'
                )
            ),
            'button_icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CEButtonObjName . ' ' . $motopressCELang->CEServiceBoxObjFontIconLabel,
                'default' => 'none',
                'list' => $this->getIconClassList(true)
            ),
            'button_icon_position' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEServiceBoxObjFontIconLabel . ' ' . $motopressCELang->CEIconAlignment,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'button_icon',
                    'except' => 'none'
                )
            ),
			'show_animation' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEObjShowAnimationLabel,
				'default' => '',
				'list' => array(
					'' => 'None',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceIn' => 'Bounce In',
					'bounceInDown' => 'Bounce In Down',
					'bounceInLeft' => 'Bounce In Left',
					'bounceInRight' => 'Bounce In Right',
					'bounceInUp' => 'Bounce In Up',
					'fadeIn' => 'Fade In',
					'fadeInDown' => 'Fade In Down',
					'fadeInDownBig' => 'Fade In Down Big',
					'fadeInLeft' => 'Fade In Left',
					'fadeInLeftBig' => 'Fade In Left Big',
					'fadeInRight' => 'Fade In Right',
					'fadeInRightBig' => 'Fade In Right Big',
					'fadeInUp' => 'Fade In Up',
					'fadeInUpBig' => 'Fade In Up Big',
					'flip' => 'Flip',
					'flipInX' => 'Flip In X',
					'flipInY' => 'Flip In Y',
					'lightSpeedIn' => 'Light Speed In',
					'rotateIn' => 'Rotate In',
					'rotateInDownLeft' => 'Rotate In Down Left',
					'rotateInDownRight' => 'Rotate In Down Right',
					'rotateInUpLeft' => 'Rotate In Up Left',
					'rotateInUpRight' => 'Rotate In Up Right',
					'rollIn' => 'Roll In',
					'zoomIn' => 'Zoom In',
					'zoomInDown' => 'Zoom In Down',
					'zoomInLeft' => 'Zoom In Left',
					'zoomInRight' => 'Zoom In Right',
					'zoomInUp' => 'Zoom In Up',
					'slideInDown' => 'Slide In Down',
					'slideInLeft' => 'Slide In Left',
					'slideInRight' => 'Slide In Right',
					'slideInUp' => 'Slide In Up',
				)
			),
			'hide_animation' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEObjHideAnimationLabel,
				'default' => '',
				'list' => array(
					'' => 'None',
					'auto' => 'Auto',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceOut' => 'Bounce Out',
					'bounceOutDown' => 'Bounce Out Down',
					'bounceOutLeft' => 'Bounce Out Left',
					'bounceOutRight' => 'Bounce Out Right',
					'bounceOutUp' => 'Bounce Out Up',
					'fadeOut' => 'Fade Out',
					'fadeOutDown' => 'Fade Out Down',
					'fadeOutDownBig' => 'Fade Out Down Big',
					'fadeOutLeft' => 'Fade Out Left',
					'fadeOutLeftBig' => 'Fade Out Left Big',
					'fadeOutRight' => 'Fade Out Right',
					'fadeOutRightBig' => 'Fade Out Right Big',
					'fadeOutUp' => 'Fade Out Up',
					'fadeOutUpBig' => 'Fade Out Up Big',
					'flip' => 'Flip',
					'flipOutX' => 'Flip Out X',
					'flipOutY' => 'Flip Out Y',
					'lightSpeedOut' => 'Light Speed Out',
					'rotateOut' => 'Rotate Out',
					'rotateOutDownLeft' => 'Rotate Out Down Left',
					'rotateOutDownRight' => 'Rotate Out Down Right',
					'rotateOutUpLeft' => 'Rotate Out Up Left',
					'rotateOutUpRight' => 'Rotate Out Up Right',
					'rollOut' => 'Roll Out',
					'zoomOut' => 'Zoom Out',
					'zoomOutDown' => 'Zoom Out Down',
					'zoomOutLeft' => 'Zoom Out Left',
					'zoomOutRight' => 'Zoom Out Right',
					'zoomOutUp' => 'Zoom Out Up',
					'slideOutDown' => 'Slide Out Down',
					'slideOutLeft' => 'Slide Out Left',
					'slideOutRight' => 'Slide Out Right',
					'slideOutUp' => 'Slide Out Up',
				)
			)
        ), 50, MPCEObject::ENCLOSED);
		$modalStyles = $buttonStyles;
		$modalStyles['mp_style_classes']['selector'] = '> button';
		$modalStyles['mp_custom_style']['selector'] = '> button';
		$modalObj->addStyle($modalStyles);

/* SPLASH SCREEN */
		$popupObj = new MPCEObject(MPCEShortcode::PREFIX . 'popup', $motopressCELang->CEPopupObjName, "popup.png", array(
            'content' => array(
				'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CEObjContent,
				'text' => $motopressCELang->edit . ' ' . $motopressCELang->CEPopupObjName,
                'default' => $motopressCELang->CEContentWithTagsDefault,
				'saveInContent' => 'true'
            ),
			'delay' => array(
				'type' => 'text',
				'label' => $motopressCELang->CEPopupObjDelayLabel,
				'default' => '1000'
			),
			'display' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEDisplay,
				'list' => array(
					'' => $motopressCELang->CEAlways,
					'once' => $motopressCELang->CEOnce
				),
				'default' => 'false'				
			),
			'modal_style' => array(
				'type' => 'radio-buttons',
				'label' => $motopressCELang->CEObjStyle,
				'default' => 'dark',
				'list' => array(
					'dark' => $motopressCELang->CEDark,
					'light' => $motopressCELang->CELight,
					'custom' => $motopressCELang->CECustom
				)
			),
			'modal_shadow_color' => array(
				'type' => 'color-picker',
                'label' => $motopressCELang->CEObjShadowColorLabel,
                'default' => '#0b0b0b',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'modal_content_color' => array(
				'type' => 'color-picker',
                'label' => $motopressCELang->CEObjContentColorLabel,
                'default' => '#ffffff',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'show_animation' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEObjShowAnimationLabel,
				'default' => 'slideInDown',
				'list' => array(
					'' => 'None',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceIn' => 'Bounce In',
					'bounceInDown' => 'Bounce In Down',
					'bounceInLeft' => 'Bounce In Left',
					'bounceInRight' => 'Bounce In Right',
					'bounceInUp' => 'Bounce In Up',
					'fadeIn' => 'Fade In',
					'fadeInDown' => 'Fade In Down',
					'fadeInDownBig' => 'Fade In Down Big',
					'fadeInLeft' => 'Fade In Left',
					'fadeInLeftBig' => 'Fade In Left Big',
					'fadeInRight' => 'Fade In Right',
					'fadeInRightBig' => 'Fade In Right Big',
					'fadeInUp' => 'Fade In Up',
					'fadeInUpBig' => 'Fade In Up Big',
					'flip' => 'Flip',
					'flipInX' => 'Flip In X',
					'flipInY' => 'Flip In Y',
					'lightSpeedIn' => 'Light Speed In',
					'rotateIn' => 'Rotate In',
					'rotateInDownLeft' => 'Rotate In Down Left',
					'rotateInDownRight' => 'Rotate In Down Right',
					'rotateInUpLeft' => 'Rotate In Up Left',
					'rotateInUpRight' => 'Rotate In Up Right',
					'rollIn' => 'Roll In',
					'zoomIn' => 'Zoom In',
					'zoomInDown' => 'Zoom In Down',
					'zoomInLeft' => 'Zoom In Left',
					'zoomInRight' => 'Zoom In Right',
					'zoomInUp' => 'Zoom In Up',
					'slideInDown' => 'Slide In Down',
					'slideInLeft' => 'Slide In Left',
					'slideInRight' => 'Slide In Right',
					'slideInUp' => 'Slide In Up',
				)
			),
			'hide_animation' => array(
				'type' => 'select',
				'label' => $motopressCELang->CEObjHideAnimationLabel,
				'default' => 'slideOutUp',
				'list' => array(
					'' => 'None',
					'auto' => 'Auto',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceOut' => 'Bounce Out',
					'bounceOutDown' => 'Bounce Out Down',
					'bounceOutLeft' => 'Bounce Out Left',
					'bounceOutRight' => 'Bounce Out Right',
					'bounceOutUp' => 'Bounce Out Up',
					'fadeOut' => 'Fade Out',
					'fadeOutDown' => 'Fade Out Down',
					'fadeOutDownBig' => 'Fade Out Down Big',
					'fadeOutLeft' => 'Fade Out Left',
					'fadeOutLeftBig' => 'Fade Out Left Big',
					'fadeOutRight' => 'Fade Out Right',
					'fadeOutRightBig' => 'Fade Out Right Big',
					'fadeOutUp' => 'Fade Out Up',
					'fadeOutUpBig' => 'Fade Out Up Big',
					'flip' => 'Flip',
					'flipOutX' => 'Flip Out X',
					'flipOutY' => 'Flip Out Y',
					'lightSpeedOut' => 'Light Speed Out',
					'rotateOut' => 'Rotate Out',
					'rotateOutDownLeft' => 'Rotate Out Down Left',
					'rotateOutDownRight' => 'Rotate Out Down Right',
					'rotateOutUpLeft' => 'Rotate Out Up Left',
					'rotateOutUpRight' => 'Rotate Out Up Right',
					'rollOut' => 'Roll Out',
					'zoomOut' => 'Zoom Out',
					'zoomOutDown' => 'Zoom Out Down',
					'zoomOutLeft' => 'Zoom Out Left',
					'zoomOutRight' => 'Zoom Out Right',
					'zoomOutUp' => 'Zoom Out Up',
					'slideOutDown' => 'Slide Out Down',
					'slideOutLeft' => 'Slide Out Left',
					'slideOutRight' => 'Slide Out Right',
					'slideOutUp' => 'Slide Out Up',
				)
			)
        ), 55, MPCEObject::ENCLOSED);
		$popupObj->addStyle(array(
			'mp_style_classes' => array(
				'basic' => array(
					'class' => 'motopress-popup-basic',
					'label' => $motopressCELang->CEPopupObjName
				),
				'selector' => false
			),
			'mp_custom_style' => array(
				'selector' => false
			)
		));

/* SERVICE BOX */
		$serviceBoxObj = new MPCEObject(MPCEShortcode::PREFIX . 'service_box', $motopressCELang->CEServiceBoxObjName, 'service-box.png', array(
            'layout' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEServiceBoxObjLayoutLabel,
                'default' => 'centered',
                'list' => array(
                    'centered' => $motopressCELang->CEServiceBoxObjLayoutCentered,
                    'heading-float' => $motopressCELang->CEServiceBoxObjLayoutHeadingFloat,
                    'text-heading-float' => $motopressCELang->CEServiceBoxObjLayoutTextHeadingFloat,
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'except' => 'big_image'
                )
            ),
            'icon_type' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEServiceBoxObjIconTypeLabel,
                'default' => 'font',
                'list' => array(
                    'font' => $motopressCELang->CEServiceBoxObjIconTypeFont,
                    'image' => $motopressCELang->CEServiceBoxObjIconTypeImage,
                    'big_image' => $motopressCELang->CEServiceBoxObjIconTypeBigImage
                )
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CEServiceBoxObjFontIconLabel,
                'default' => 'fa fa-star-o',
                'list' => $this->getIconClassList(),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'font'
                ),
            ),
            'icon_size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEServiceBoxObjIconSizeLabel,
                'default' => 'normal',
                'list' => array(
                    'mini' => $motopressCELang->CEMini,
                    'small' => $motopressCELang->CESmall,
                    'normal' => $motopressCELang->CENormal,
                    'large' => $motopressCELang->CELarge,
                    'extra-large' => $motopressCELang->CEExtraLarge,
                    'custom' => $motopressCELang->CECustom,
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'font'
                )
            ),
            'icon_custom_size' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEServiceBoxObjIconCustomSizeLabel,
                'description' => $motopressCELang->CEServiceBoxObjIconCustomSizeDesc,
                'min' => 1,
                'step' => 1,
                'max' => 500,
                'default' => 26,
                'dependency' => array(
                    'parameter' => 'icon_size',
                    'value' => 'custom'
                )
            ),
            'icon_color' => array(
                'type' => 'color-select',
                'label' => $motopressCELang->CEServiceBoxObjIconColorLabel,
                'default' => 'mp-text-color-default',
                'list' => array(
                    'mp-text-color-default' => $motopressCELang->CESilver,
                    'mp-text-color-red' => $motopressCELang->CERed,
                    'mp-text-color-pink-dreams' => $motopressCELang->CEPinkDreams,
                    'mp-text-color-warm' => $motopressCELang->CEWarm,
                    'mp-text-color-hot-summer' => $motopressCELang->CEHotSummer,
                    'mp-text-color-olive-garden' => $motopressCELang->CEOliveGarden,
                    'mp-text-color-green-grass' => $motopressCELang->CEGreenGrass,
                    'mp-text-color-skyline' => $motopressCELang->CESkyline,
                    'mp-text-color-aqua-blue' => $motopressCELang->CEAquaBlue,
                    'mp-text-color-violet' => $motopressCELang->CEViolet,
                    'mp-text-color-dark-grey' => $motopressCELang->CEDarkGrey,
                    'mp-text-color-black' => $motopressCELang->CEBlack,
                    'custom' => $motopressCELang->CECustom,
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'font'
                )
            ),
            'icon_custom_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEServiceBoxObjIconCustomColorLabel,
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'icon_color',
                    'value' => 'custom'
                )
            ),
            'image_id' => array(
                'type' => 'image',
                'label' => $motopressCELang->CEServiceBoxObjImageIconLabel,
                'default' => '',
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => array('image', 'big_image')
                )
            ),
            'image_size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEServiceBoxObjImageIconSizeLabel,
                'default' => 'thumbnail',
                'list' => array(
//                    'large' => $motopressCELang->CELarge,
//                    'medium' => $motopressCELang->CEMedium,
                    'thumbnail' => $motopressCELang->CEThumbnail,
                    'custom' => $motopressCELang->CECustom,
                    'full' => $motopressCELang->CEFull
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'image'
                )
            ),
            'image_custom_size' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEServiceBoxObjImageIconCustomSizeLabel,
                'description' => $motopressCELang->CEServiceBoxObjImageIconCustomSizeDesc,
                'default' => '50x50',
                'dependency' => array(
                    'parameter' => 'image_size',
                    'value' => 'custom'
                )
            ),
            'big_image_height' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEServiceBoxObjImageIconHeightLabel,
                'default' => 150,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'big_image'
                )
            ),
            'icon_background_type' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEServiceBoxObjIconBackgroundTypeLabel,
                'default' => 'none',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'square' => $motopressCELang->CESquare,
                    'rounded' => $motopressCELang->CERounded,
                    'circle' => $motopressCELang->CECircle,
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'except' => 'big_image'
                )
            ),
            'icon_background_size' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEServiceBoxObjIconBackgroundSize,
                'default' => 1.5,
                'min' => 1,
                'max' => 3,
                'step' => 0.1,
                'dependency' => array(
                    'parameter' => 'icon_background_type',
                    'except' => 'none'
                )
            ),
            'icon_background_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEServiceBoxObjIconBackgroundColorLabel,
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'icon_background_type',
                    'except' => 'none'
                )
            ),
            'icon_margin_left' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEIconMarginLeftLabel,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_margin_right' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEIconMarginRightLabel,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_margin_top' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEIconMarginTopLabel,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_margin_bottom' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEIconMarginBottomLabel,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_effect' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEServiceBoxObjIconEffectLabel,
                'default' => 'none',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'grayscale' => $motopressCELang->CEServiceBoxObjIconEffectGrayscale,
                    'zoom' => $motopressCELang->CEServiceBoxObjIconEffectZoom,
                    'rotate' => $motopressCELang->CEServiceBoxObjIconEffectRotate
                )
            ),
            'heading' => array(
                'type' => 'longtext',
                'label' => $motopressCELang->CEServiceBoxObjHeadingLabel,
                'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'text' => $motopressCELang->CEOpenInWPEditor,
                'saveInContent' => 'false'
            ),
            'heading_tag' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEServiceBoxObjHeadingTagLabel,
                'default' => 'h2',
                'list' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6'
                )
            ),
            'text' => array(
                'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CEServiceBoxObjTextLabel,
                'default' => '<p>Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>',
                'text' => $motopressCELang->CEOpenInWPEditor,
                'saveInContent' => 'true'
            ),
            'button_show' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEServiceBoxObjShowButtonLabel,
                'default' => 'true'
            ),
            'button_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEServiceBoxObjButtonTextLabel,
                'default' => 'Button',
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            ),
            'button_link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEServiceBoxObjButtonLinkLabel,
                'default' => '#',
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            ),
            'button_color' => array(
                'type' => 'color-select',
                'label' => $motopressCELang->CEServiceBoxObjButtonColorLabel,
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'motopress-btn-color-silver' => $motopressCELang->CESilver,
                    'motopress-btn-color-red' => $motopressCELang->CERed,
                    'motopress-btn-color-pink-dreams' => $motopressCELang->CEPinkDreams,
                    'motopress-btn-color-warm' => $motopressCELang->CEWarm,
                    'motopress-btn-color-hot-summer' => $motopressCELang->CEHotSummer,
                    'motopress-btn-color-olive-garden' => $motopressCELang->CEOliveGarden,
                    'motopress-btn-color-green-grass' => $motopressCELang->CEGreenGrass,
                    'motopress-btn-color-skyline' => $motopressCELang->CESkyline,
                    'motopress-btn-color-aqua-blue' => $motopressCELang->CEAquaBlue,
                    'motopress-btn-color-violet' => $motopressCELang->CEViolet,
                    'motopress-btn-color-dark-grey' => $motopressCELang->CEDarkGrey,
                    'motopress-btn-color-black' => $motopressCELang->CEBlack,
                    'custom' => $motopressCELang->CECustom,
                ),
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            ),
            'button_custom_bg_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEServiceBoxObjButtonCustomBGColorLabel,
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'button_color',
                    'value' => 'custom'
                )
            ),
            'button_custom_text_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEServiceBoxObjButtonCustomTextColorLabel,
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'button_color',
                    'value' => 'custom'
                )
            ),
            'button_align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'center',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            )
        ), 15, MPCEObject::ENCLOSED);

        $serviceBoxObj->addStyle(array(
           'mp_style_classes' => array(
               'basic' => array(
                   'class' => 'motopress-service-box-basic',
                   'label' => $motopressCELang->CEServiceBoxObjBasicClassLabel
               )
           )
        ));

/* LIST */
        $listObj = new MPCEObject(MPCEShortcode::PREFIX . 'list', $motopressCELang->CEListObjName, 'list.png', array(
            'list_type' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEStyle,
                'default' => 'icon',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'icon' => $motopressCELang->CEListObjListTypeIcon,
                    'circle' => $motopressCELang->CEListObjListTypeCircle,
                    'disc' => $motopressCELang->CEListObjListTypeDisc,
                    'square' => $motopressCELang->CEListObjListTypeSquare,
                    'armenian' => $motopressCELang->CEListObjListTypeArmenian,
                    'georgian' => $motopressCELang->CEListObjListTypeGeorgian,
                    'decimal' => '1, 2, 3, 4',
                    'decimal-leading-zero' => '01, 02, 03, 04',
                    'lower-latin' => 'a, b, c, d',
                    'lower-roman' => 'i, ii, iii, iv',
                    'lower-greek' => 'α, β, γ, δ',
                    'upper-latin' => 'A, B, C, D',
                    'upper-roman' => 'I, II, III, IV'
                )
            ),			
            'items' => array(
                'type' => 'longtext-table',
                'label' => $motopressCELang->CEListObjItemsLabel,
                'default' => 'Lorem<br />Ipsum<br />Dolor',
                'saveInContent' => 'true'
            ),
			'use_custom_text_color' => array(
				'type' => 'checkbox',
				'label' => $motopressCELang->CEUseCustomTextColor,
				'default' => 'false'
			), 
            'text_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEListObjTextColorLabel,
                'default' => '#000000',
				'dependency' => array(
                    'parameter' => 'use_custom_text_color',
                    'except' => 'false'
                )
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CEListObjIconLabel,
                'default' => 'fa fa-star',
                'list' => $this->getIconClassList(),
                'dependency' => array(
                    'parameter' => 'list_type',
                    'value' => 'icon'
                )
            ),
			'use_custom_icon_color' => array(
				'type' => 'checkbox',
				'label' => $motopressCELang->CEUseCustomIconColor,
				'default' => 'false',
                'dependency' => array(
                    'parameter' => 'list_type',
                    'value' => 'icon'
                )
			),
			'icon_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEServiceBoxObjIconColorLabel,
                'default' => '#000000',
				'dependency' => array(
                    'parameter' => 'use_custom_icon_color',
                    'except' => 'false'
                )
            ),
        ), 60, MPCEObject::ENCLOSED);
		$listObj->addStyle(array(
			'mp_style_classes' => array(
				'basic' => array(
					'class' => 'motopress-list-obj-basic',
					'label' => $motopressCELang->CEListObjName
				)
           )
		));

        $buttonInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'button_inner', $motopressCELang->CEButtonObjName, null, array(
			'text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEButtonObjTextLabel,
                'default' => $motopressCELang->CEButtonObjName
            ),
            'link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEButtonObjLinkLabel,
                'default' => '#',
                'description' => $motopressCELang->CEButtonObjLinkDesc
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEOpenLinkInNewWindow,
                'default' => 'false'
            ),
            'color' => array(
                'type' => 'color-select',
                'label' => $motopressCELang->CEButtonObjColorLabel,
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'custom' => $motopressCELang->CECustom,
                    'motopress-btn-color-silver' => $motopressCELang->CESilver,
                    'motopress-btn-color-red' => $motopressCELang->CERed,
                    'motopress-btn-color-pink-dreams' => $motopressCELang->CEPinkDreams,
                    'motopress-btn-color-warm' => $motopressCELang->CEWarm,
                    'motopress-btn-color-hot-summer' => $motopressCELang->CEHotSummer,
                    'motopress-btn-color-olive-garden' => $motopressCELang->CEOliveGarden,
                    'motopress-btn-color-green-grass' => $motopressCELang->CEGreenGrass,
                    'motopress-btn-color-skyline' => $motopressCELang->CESkyline,
                    'motopress-btn-color-aqua-blue' => $motopressCELang->CEAquaBlue,
                    'motopress-btn-color-violet' => $motopressCELang->CEViolet,
                    'motopress-btn-color-dark-grey' => $motopressCELang->CEDarkGrey,
                    'motopress-btn-color-black' => $motopressCELang->CEBlack
                )
            ),
            'custom_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEButtonObjCustomColorLabel,
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'color',
                    'value' => 'custom'
                )
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CEServiceBoxObjFontIconLabel,
                'default' => 'none',
                'list' => $this->getIconClassList(true),
            )
		), null, MPCEObject::SELF_CLOSED, MPCEObject::RESIZE_NONE, false);
		
		$buttonInnerObj->addStyle(array(
			'mp_style_classes' => array(
				'basic' => array(
					'class' => 'motopress-btn',
					'label' => $motopressCELang->CEButtonObjBasicClassLabel
				)
			)
		));		
				
/* BUTTON GROUP */        
        $buttonGroupObj = new MPCEObject(MPCEShortcode::PREFIX . 'button_group', $motopressCELang->CEButtonGroupObjName, 'button-group.png', array(
            'elements' => array(
                'type' => 'group',
                'contains' => MPCEShortcode::PREFIX . 'button_inner',
                'items' => array(
                    'label' => array(
                        'default' => $motopressCELang->CEButtonObjTextLabel,
                        'parameter' => 'text'
                    ),
                    'count' => 2
                ),
                'text' => strtr($motopressCELang->CEAddNewItem, array('%name%' => $motopressCELang->CEButtonObjName)),
                /*'activeParameter' => 'active',
                'rules' => array(
                    'rootSelector' => '.motopress-button-obj > .motopress-btn',
                    'activeSelector' => '',
                    'activeClass' => 'ui-state-active'
                ),
                'events' => array(
                    'onActive' => array(
                        'selector' => '> a',
                        'event' => 'click'
                    )
                )*/
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                )
            ),
			'group_layout' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CELayout,
                'default' => 'horizontal',
                'list' => array(
                    'horizontal' => $motopressCELang->CEHorizontal,
                    'vertical' => $motopressCELang->CEVertical
                )
            ),
            'indent' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEButtonGroupObjIndentLabel,
                'default' => '5',
				'list' => array(
                    '0' => '0',
                    '2' => '2',
                    '5' => '5',
                    '10' => '10',
                    '15' => '15',
                    '25' => '25',
                )
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEButtonGroupObjSizeLabel,
                'default' => 'middle',
                'list' => array(
                    'mini' => $motopressCELang->CEMini,
                    'small' => $motopressCELang->CESmall,
                    'middle' => $motopressCELang->CEMiddle,
                    'large' => $motopressCELang->CELarge
                )
            ),
            'icon_position' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEIconAlignment,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight
                )
            ),
            'icon_indent' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEIconIndent,
                'default' => 'small',
                'list' => array(
                    'mini' => $motopressCELang->CEMini . ' ' . $motopressCELang->CEIconIndent,
                    'small' => $motopressCELang->CESmall . ' ' . $motopressCELang->CEIconIndent,
                    'middle' => $motopressCELang->CEMiddle . ' ' . $motopressCELang->CEIconIndent,
                    'large' => $motopressCELang->CELarge . ' ' . $motopressCELang->CEIconIndent
                )
            )
        ), 20, MPCEObject::ENCLOSED);

/* CALL TO ACTION */
        $ctaObj = new MPCEObject(MPCEShortcode::PREFIX . 'cta', $motopressCELang->CECTAObjName, 'call-to-action.png', array(
            'heading' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEHeadingObjName,
                'default' => 'Lorem ipsum dolor'
            ),
            'subheading' => array(
                'type' => 'text',
                'label' => $motopressCELang->CESubheadingObjName,
                'default' => 'Lorem ipsum dolor sit amet'
            ),
			'content_text' => array(
                'type' => 'longtext',
                'label' => $motopressCELang->CEText,
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
            ),
            'text_align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CETextAlignName,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight,
                    'justify' => $motopressCELang->CEJustify
                )
            ),
            'shape' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEShape,
                'default' => 'rounded',
                'list' => array(
                    'square' => $motopressCELang->CESquare,
                    'rounded' => $motopressCELang->CERounded,
                    'round' => $motopressCELang->CERound
                )
            ),
            'style' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEStyle,
                'default' => '3d',
                'list' => array(
                    'classic' => $motopressCELang->CEClassic,
                    'flat' => $motopressCELang->CEFlat,
                    'outline' => $motopressCELang->CEOutline,
                    '3d' => $motopressCELang->CE3D,
                    'custom' => $motopressCELang->CECustom
                )
            ),
            'style_bg_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEBgColor,
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'style',
                    'value' => 'custom'
                )
            ),
            'style_text_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CETextColor,
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'style',
                    'value' => 'custom'
                )
            ),
            'width' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CECTAObjWidthLabel,
                'min' => 50,
                'max' => 100,
                'step' => 1,
                'default' => 100
            ),
            'button_pos' => array(
                'type' => 'select',
                'label' => $motopressCELang->CECTAObjButtonPosLabel,
                'default' => 'right',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'top' => $motopressCELang->CETop,
                    'bottom' => $motopressCELang->CEBottom,
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight
                )
            ),
			'button_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEButtonObjTextLabel,
                'default' => $motopressCELang->CEButtonObjName,
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CECTAObjButtonLinkLabel,
                'default' => '#',
                'description' => $motopressCELang->CEButtonObjLinkDesc,
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_target' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEOpenLinkInNewWindow,
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CECTAObjButtonAlignLabel,
                'default' => 'center',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                ),
				'dependency' => array(
					'parameter' => 'button_pos',
                    'value' => array('top', 'bottom')
				)
            ),
            'button_shape' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CECTAObjButtonShapeLabel,
                'default' => 'rounded',
                'list' => array(
                    'square' => $motopressCELang->CESquare,
                    'rounded' => $motopressCELang->CERounded,
                    'round' => $motopressCELang->CERound
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_color' => array(
                'type' => 'color-select',
                'label' => $motopressCELang->CEButtonObjColorLabel,
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'motopress-btn-color-silver' => $motopressCELang->CESilver,
                    'motopress-btn-color-red' => $motopressCELang->CERed,
                    'motopress-btn-color-pink-dreams' => $motopressCELang->CEPinkDreams,
                    'motopress-btn-color-warm' => $motopressCELang->CEWarm,
                    'motopress-btn-color-hot-summer' => $motopressCELang->CEHotSummer,
                    'motopress-btn-color-olive-garden' => $motopressCELang->CEOliveGarden,
                    'motopress-btn-color-green-grass' => $motopressCELang->CEGreenGrass,
                    'motopress-btn-color-skyline' => $motopressCELang->CESkyline,
                    'motopress-btn-color-aqua-blue' => $motopressCELang->CEAquaBlue,
                    'motopress-btn-color-violet' => $motopressCELang->CEViolet,
                    'motopress-btn-color-dark-grey' => $motopressCELang->CEDarkGrey,
                    'motopress-btn-color-black' => $motopressCELang->CEBlack
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEButtonGroupObjSizeLabel,
                'default' => 'large',
                'list' => array(
                    'mini' => $motopressCELang->CEMini,
                    'small' => $motopressCELang->CESmall,
                    'middle' => $motopressCELang->CEMiddle,
                    'large' => $motopressCELang->CELarge
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_icon' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CECTAObjButtonIconLabel,
                'default' => 'none',
                'list' => $this->getIconClassList(true),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_icon_position' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CECTAObjButtonIconAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight
                ),
                'dependency' => array(
                    'parameter' => 'button_icon',
                    'except' => 'none'
                )
            ),
            'button_animation' => array(
                'type' => 'select',
                'label' => $motopressCELang->CECTAObjButtonAnimationLabel,
                'default' => 'right-to-left',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'top-to-bottom' => $motopressCELang->CEToBottom,
                    'bottom-to-top' => $motopressCELang->CEToTop,
                    'left-to-right' => $motopressCELang->CEToRight,
                    'right-to-left' => $motopressCELang->CEToLeft,
                    'appear' => $motopressCELang->CEAnimationAppear
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'icon_pos' => array(
                'type' => 'select',
                'label' => $motopressCELang->CECTAObjIconPosLabel,
                'default' => 'left',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'top' => $motopressCELang->CETop,
                    'bottom' => $motopressCELang->CEBottom,
                    'left' => $motopressCELang->CELeft,
                    'right' => $motopressCELang->CERight
                )
            ),
			'icon_type' => array(
                'type' => 'icon-picker',
                'label' => $motopressCELang->CECTAObjIconTypeLabel,
                'default' => 'fa fa-info-circle',
                'list' => $this->getIconClassList(),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_color' => array(
                'type' => 'color-select',
                'label' => $motopressCELang->CECTAObjIconColorLabel,
                'default' => 'custom',
                'list' => array(
                    'mp-text-color-default' => $motopressCELang->CESilver,
                    'mp-text-color-red' => $motopressCELang->CERed,
                    'mp-text-color-pink-dreams' => $motopressCELang->CEPinkDreams,
                    'mp-text-color-warm' => $motopressCELang->CEWarm,
                    'mp-text-color-hot-summer' => $motopressCELang->CEHotSummer,
                    'mp-text-color-olive-garden' => $motopressCELang->CEOliveGarden,
                    'mp-text-color-green-grass' => $motopressCELang->CEGreenGrass,
                    'mp-text-color-skyline' => $motopressCELang->CESkyline,
                    'mp-text-color-aqua-blue' => $motopressCELang->CEAquaBlue,
                    'mp-text-color-violet' => $motopressCELang->CEViolet,
                    'mp-text-color-dark-grey' => $motopressCELang->CEDarkGrey,
                    'mp-text-color-black' => $motopressCELang->CEBlack,
                    'custom' => $motopressCELang->CECustom
                ),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_custom_color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CECTAObjIconCustomColorLabel,
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'icon_color',
                    'value' => 'custom'
                )
            ),
            'icon_size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CECTAObjIconSizeLabel,
                'default' => 'extra-large',
                'list' => array(
                    'mini' => $motopressCELang->CEMini,
                    'small' => $motopressCELang->CESmall,
                    'normal' => $motopressCELang->CENormal,
                    'large' => $motopressCELang->CELarge,
                    'extra-large' => $motopressCELang->CEExtraLarge,
                    'custom' => $motopressCELang->CECustom,
                ),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_custom_size' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CECTAObjIconCustomSizeLabel,
                'description' => $motopressCELang->CEServiceBoxObjIconCustomSizeDesc,
                'min' => 1,
                'step' => 1,
                'max' => 500,
                'default' => 26,
                'dependency' => array(
                    'parameter' => 'icon_size',
                    'value' => 'custom'
                )
            ),
            'icon_on_border' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CECTAObjIconOnBorderLabel,
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_animation' => array(
                'type' => 'select',
                'label' => $motopressCELang->CECTAObjIconAnimationLabel,
                'default' => 'left-to-right',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'top-to-bottom' => $motopressCELang->CEToBottom,
                    'bottom-to-top' => $motopressCELang->CEToTop,
                    'left-to-right' => $motopressCELang->CEToRight,
                    'right-to-left' => $motopressCELang->CEToLeft,
                    'appear' => $motopressCELang->CEAnimationAppear
                ),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'animation' => array(
                'type' => 'select',
                'label' => $motopressCELang->CECTAObjAnimationLabel,
                'default' => 'none',
                'list' => array(
                    'none' => $motopressCELang->CENone,
                    'top-to-bottom' => $motopressCELang->CEToBottom,
                    'bottom-to-top' => $motopressCELang->CEToTop,
                    'left-to-right' => $motopressCELang->CEToRight,
                    'right-to-left' => $motopressCELang->CEToLeft,
                    'appear' => $motopressCELang->CEAnimationAppear
                )
            )            
        ), 45);
		$ctaObj->addStyle(array(
			'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-cta-obj-basic',
                    'label' => 'Call To Action'
                ),
            )
		));

/* SLIDER PLUGIN */
        $mpSliderObj = null;
        if (is_plugin_active('motopress-slider/motopress-slider.php') || is_plugin_active('motopress-slider-lite/motopress-slider.php')) {
            global $mpsl_settings;
            if (version_compare($mpsl_settings['plugin_version'], '1.1.2', '>=')) {
                global $mpSlider;
                $mpSliderObj = new MPCEObject('mpsl', apply_filters('mpsl_product_name', $motopressCELang->CESliderObjName), 'layer-slider.png', array(
                    'alias' => array(
                        'type' => 'select',
                        'label' => $motopressCELang->CESliderNameLabel,
                        'description' => $motopressCELang->CESliderNameDesc,
                        'list' => array_merge(
                            array('' => $motopressCELang->CESliderNameNotSelected),
                            $mpSlider->getSliderList('title', 'alias')
                        )
                    )
                ), 40);
            }
        }

// WORDPRESS
        // WP Widgets Area
        global $wp_registered_sidebars;
        $wpWidgetsArea_array = array();
        $wpWidgetsArea_default = '';
        if ( $wp_registered_sidebars ){
            foreach ( $wp_registered_sidebars as $sidebar ) {
                if (empty($wpWidgetsArea_default))
                        $wpWidgetsArea_default = $sidebar['id'];
                $wpWidgetsArea_array[$sidebar['id']] = $sidebar['name'];
            }
        }else {
            $wpWidgetsArea_array['no'] = $motopressCELang->CEwpWidgetsAreaNoSidebars;
        }
        $wpWidgetsAreaObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_widgets_area', $motopressCELang->CEwpWidgetsArea, 'sidebar.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => '',
                'description' => $motopressCELang->CEwpWidgetsAreaDescription
            ),
            'sidebar' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpWidgetsAreaSelect,
                'default' => $wpWidgetsArea_default,
                'description' => '',
                'list' => $wpWidgetsArea_array
            )
        ), 5);

        // archives
        $wpArchiveObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_archives', $motopressCELang->CEwpArchives, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpArchives,
                'description' => $motopressCELang->CEwpArchivesDescription
            ),
            'dropdown' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpDisplayAsDropDown,
                'default' => '',
                'description' => ''
            ),
            'count' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpShowPostCounts,
                'default' => '',
                'description' => ''
            )
        ), 45);

        // calendar
        $wpCalendarObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_calendar', $motopressCELang->CEwpCalendar, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpCalendar,
                'description' => $motopressCELang->CEwpCalendarDescription
            )
        ), 30);

        // wp_categories
        $wpCategoriesObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_categories', $motopressCELang->CEwpCategories, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpCategories,
                'description' => $motopressCELang->CEwpCategoriesDescription
            ),
            'dropdown' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpDisplayAsDropDown,
                'default' => '',
                'description' => ''
            ),
            'count' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpShowPostCounts,
                'default' => '',
                'description' => ''
            ),
            'hierarchy' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpCategoriesShowHierarchy,
                'default' => '',
                'description' => ''
            )
        ), 40);

        // wp_navmenu
        $wpCustomMenu_menus = get_terms('nav_menu');
        $wpCustomMenu_array = array();
        $wpCustomMenu_default = '';
        if ($wpCustomMenu_menus){
            foreach($wpCustomMenu_menus as $menu){
                if (empty($wpCustomMenu_default))
                    $wpCustomMenu_default = $menu->slug;
                $wpCustomMenu_array[$menu->slug] = $menu->name;
            }
        }else{
            $wpCustomMenu_array['no'] = $motopressCELang->CEwpCustomMenuNoMenus;
        }
        $wpCustomMenuObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_navmenu', $motopressCELang->CEwpCustomMenu, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpCustomMenu,
                'description' => $motopressCELang->CEwpCustomMenuDescription
            ),
            'nav_menu' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpCustomMenuSelectMenu,
                'default' => $wpCustomMenu_default,
                'description' => '',
                'list' => $wpCustomMenu_array
            )
        ), 10);

        // wp_meta
        $wpMetaObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_meta', $motopressCELang->CEwpMeta, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpMeta,
                'description' => $motopressCELang->CEwpMetaDescription
            )
        ), 55);

        // wp_pages
        $wpPagesObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_pages', $motopressCELang->CEwpPages, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpPages,
                'description' => $motopressCELang->CEwpPagesDescription
            ),
            'sortby' => array(
                'type' => 'select',
                'label' => $motopressCELang->CESortBy,
                'default' => 'menu_order',
                'description' => '',
                'list' => array(
                    'post_title' => $motopressCELang->CESortByPageTitle,
                    'menu_order' => $motopressCELang->CESortByPageOrder,
                    'ID' => $motopressCELang->CESortByPageID
                ),
            ),
            'exclude' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEExclude,
                'default' => '',
                'description' => $motopressCELang->CEwpPagesExcludePages
            )
        ), 15);

        // wp_posts
        $wpPostsObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_posts', $motopressCELang->CEwpRecentPosts, 'wordpress.png', array(
            'title' => array(
                    'type' => 'text',
                    'label' => $motopressCELang->CEParametersTitle,
                    'default' => $motopressCELang->CEwpRecentPosts,
                    'description' => $motopressCELang->CEwpRecentPostsDescription
            ),
            'number' => array(
                    'type' => 'text',
                    'label' => $motopressCELang->CEwpRecentPostsNumber,
                    'default' => '5',
                    'description' => ''
            ),
            'show_date' => array(
                    'type' => 'checkbox',
                    'label' => $motopressCELang->CEwpRecentPostsDisplayDate,
                    'default' => '',
                    'description' => ''
            )
        ), 20);

        // wp_comments
        $wpRecentCommentsObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_comments', $motopressCELang->CEwpRecentComments, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpRecentComments,
                'description' => $motopressCELang->CEwpRecentCommentsDescription
            ),
            'number' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpRecentCommentsNumber,
                'default' => '5',
                'description' => ''
            )
        ), 25);

        // wp_rss
        $wpRSSObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_rss', $motopressCELang->CEwpRSS, 'wordpress.png', array(
            'url' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpRSSUrl,
                'default' => 'http://www.getmotopress.com/feed/',
                'description' => $motopressCELang->CEwpRSSUrlDescription
            ),
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpRSSFeedTitle,
                'default' => '',
                'description' => $motopressCELang->CEwpRSSFeedTitleDescription
            ),
            'items' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpRSSQuantity,
                'default' => 9,
                'description' => $motopressCELang->CEwpRSSQuantityDescription,
                'list' => range(1, 20),
            ),
            'show_summary' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpRSSDisplayContent,
                'default' => '',
                'description' => ''
            ),
            'show_author' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpRSSDisplayAuthor,
                'default' => '',
                'description' => ''
            ),
            'show_date' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpRSSDisplayDate,
                'default' => '',
                'description' => ''
            )
        ), 50);

        // search
        $wpSearchObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_search', $motopressCELang->CEwpRSSSearch, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpRSSSearch,
                'description' => $motopressCELang->CEwpRSSSearchDescription
            )
        ), 35);

        // tag cloud
        $wpTagCloudObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_tagcloud', $motopressCELang->CEwpTagCloud, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpTags,
                'description' => $motopressCELang->CEwpTagCloudDescription
            ),
            'taxonomy' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpTagCloudTaxonomy,
                'default' => 10,
                'description' => '',
                'list' => array(
                    'post_tag' => $motopressCELang->CEwpTags,
                    'category' => $motopressCELang->CEwpTagCloudCategories,
                )
            )
        ), 60);
        /* wp widgets END */

        /* Groups */
        $gridGroup = new MPCEGroup();
        $gridGroup->setId(MPCEShortcode::PREFIX . 'grid');
        $gridGroup->setName($motopressCELang->CEGridGroupName);
        $gridGroup->setShow(false);
        $gridGroup->addObject(array($rowObj, $rowInnerObj, $spanObj, $spanInnerObj));

        $textGroup = new MPCEGroup();
        $textGroup->setId(MPCEShortcode::PREFIX . 'text');
        $textGroup->setName($motopressCELang->CETextGroupName);
        $textGroup->setIcon('text.png');
        $textGroup->setPosition(0);
        $textGroup->addObject(array($textObj /*20*/, $headingObj /*10*/, $codeObj /*30*/, $quotesObj /*40*/, $membersObj /*50*/, $listObj /*60*/, $iconObj /*70*/));

        $imageGroup = new MPCEGroup();
        $imageGroup->setId(MPCEShortcode::PREFIX . 'image');
        $imageGroup->setName($motopressCELang->CEImageGroupName);
        $imageGroup->setIcon('image.png');
        $imageGroup->setPosition(10);
        $imageGroup->addObject(array($imageObj, $imageSlider, $gridGalleryObj, $mpSliderObj));

        $buttonGroup = new MPCEGroup();
        $buttonGroup->setId(MPCEShortcode::PREFIX . 'button');
        $buttonGroup->setName($motopressCELang->CEButtonGroupName);
        $buttonGroup->setIcon('button.png');
        $buttonGroup->setPosition(20);
        $buttonGroup->addObject(array($buttonObj, $downloadButtonObj, $buttonInnerObj, $buttonGroupObj, $socialsObj, $socialProfileObj));

        $mediaGroup = new MPCEGroup();
        $mediaGroup->setId(MPCEShortcode::PREFIX . 'media');
        $mediaGroup->setName($motopressCELang->CEMediaGroupName);
        $mediaGroup->setIcon('media.png');
        $mediaGroup->setPosition(30);
        $mediaGroup->addObject(array($videoObj, $wpAudioObj));

        $otherGroup = new MPCEGroup();
        $otherGroup->setId(MPCEShortcode::PREFIX . 'other');
        $otherGroup->setName($motopressCELang->CEOtherGroupName);
        $otherGroup->setIcon('other.png');
        $otherGroup->setPosition(40);
        $otherGroup->addObject(array(
            $postsGridObj,          /* 10 */
            $serviceBoxObj,         /* 15 */
            $tabsObj,               /* 20 */
            $accordionObj,          /* 25 */
            $tableObj,              /* 30 */
            $postsSliderObj,        /* 35 */
            $ctaObj,                /* 45 */
            $modalObj,              /* 50 */
            $popupObj,              /* 55 */
            $spaceObj,              /* 60 */
            $gMapObj,               /* 65 */
            $countdownTimerObj,     /* 70 */
            $embedObj,              /* 75 */
            $googleChartsObj,       /* 80 */
            $tabObj,
            $accordionItemObj,
        ));

        $wordpressGroup = new MPCEGroup();
        $wordpressGroup->setId(MPCEShortcode::PREFIX . 'wordpress');
        $wordpressGroup->setName($motopressCELang->CEWordPressGroupName);
        $wordpressGroup->setIcon('wordpress.png');
        $wordpressGroup->setPosition(50);
        $wordpressGroup->addObject(array($wpArchiveObj, $wpCalendarObj, $wpCategoriesObj, $wpCustomMenuObj, $wpMetaObj, $wpPagesObj, $wpPostsObj, $wpRecentCommentsObj, $wpRSSObj, $wpSearchObj, $wpTagCloudObj, $wpWidgetsAreaObj));

        self::$defaultGroup = $otherGroup->getId();

        $this->addGroup(array($gridGroup, $textGroup, $imageGroup, $buttonGroup, $mediaGroup, $otherGroup, $wordpressGroup));

        $this->updateDeprecatedParams();

        /* Templates */
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/templates/landing.php';
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/templates/callToAction.php';
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/templates/feature.php';
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/templates/description.php';
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/templates/service.php';
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/templates/product.php';

        $landingTemplate = new MPCETemplate(MPCEShortcode::PREFIX . 'landing_page', $motopressCELang->CELandingTemplate . ' ' . $motopressCELang->CEPage, $landingContent, 'landing-page.png');

        $callToActionTemplate = new MPCETemplate(MPCEShortcode::PREFIX . 'call_to_action_page', $motopressCELang->CECallToActionTemplate . ' ' . $motopressCELang->CEPage, $callToActionContent, 'call-to-action-page.png');

        $featureTemplate = new MPCETemplate(MPCEShortcode::PREFIX . 'feature_list', $motopressCELang->CEFeatureTemplate . ' ' . $motopressCELang->CEList, $featureContent, 'feature-list.png');

        $descriptionTemplate = new MPCETemplate(MPCEShortcode::PREFIX . 'description_page', $motopressCELang->CEDescriptionTemplate . ' ' . $motopressCELang->CEPage, $descriptionContent, 'description-page.png');

        $serviceTemplate = new MPCETemplate(MPCEShortcode::PREFIX . 'service_list', $motopressCELang->CEServiceTemplate . ' ' . $motopressCELang->CEList, $serviceContent, 'service-list.png');

        $productTemplate = new MPCETemplate(MPCEShortcode::PREFIX . 'product_page', $motopressCELang->CEProductTemplate . ' ' . $motopressCELang->CEPage, $productContent, 'product-page.png');

        $this->addTemplate(array($landingTemplate, $callToActionTemplate, $featureTemplate, $descriptionTemplate, $serviceTemplate, $productTemplate));

        do_action_ref_array('mp_library', array(&$this));
    }

    /**
     * @return MPCEGroup[]
     */
    public function getLibrary() {
        return $this->library;
    }

    /**
     * @param string $id
     * @return MPCEGroup|boolean
     */
    public function &getGroup($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->library)) {
                        return $this->library[$id];
                    }
                }
            }
        }
        $group = false;
        return $group;
    }

    /**
     * @param MPCEGroup|MPCEGroup[] $group
     */
    public function addGroup($group) {
        if ($group instanceof MPCEGroup) {
            if ($group->isValid()) {
                if (!array_key_exists($group->getId(), $this->library)) {
                    if (count($group->getObjects()) > 0) {
                        $this->library[$group->getId()] = $group;
                    }
                }
            } else {
                if (!self::$isAjaxRequest) {
                    $group->showErrors();
                }
            }
        } elseif (is_array($group)) {
            if (!empty($group)) {
                foreach ($group as $g) {
                    if ($g instanceof MPCEGroup) {
                        if ($g->isValid()) {
                            if (!array_key_exists($g->getId(), $this->library)) {
                                if (count($g->getObjects()) > 0) {
                                    $this->library[$g->getId()] = $g;
                                }
                            }
                        } else {
                            if (!self::$isAjaxRequest) {
                                $g->showErrors();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function removeGroup($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->library)) {
                        unset($this->library[$id]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param string $id
     * @return MPCEObject|boolean
     */
    public function &getObject($id) {
        foreach ($this->library as $group) {
            $object = &$group->getObject($id);
            if ($object) return $object;
        }
        $object = false;
        return $object;
    }

    /**
     * @param MPCEObject|MPCEObject[] $object
     * @param string $group [optional]
     */
    public function addObject($object, $group = 'mp_other') {
        $groupObj = &$this->getGroup($group);
        if (!$groupObj) { //for support versions less than 1.5 where group id without MPCEShortcode::PREFIX
            $groupObj = &$this->getGroup(MPCEShortcode::PREFIX . $group);
        }
        if (!$groupObj) {
            $groupObj = &$this->getGroup(self::$defaultGroup);
        }
        if ($groupObj) {
            $groupObj->addObject($object);
        }
    }

    /**
     * @param string $id
     */
    public function removeObject($id) {
        foreach ($this->library as $group) {
            if ($group->removeObject($id)) break;
        }
    }

    /**
     * @return MPCETemplate[]
     */
    public function getTemplates() {
        return $this->templates;
    }

    /**
     * @param string $id
     * @return MPCETemplate|boolean
     */
    public function &getTemplate($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->templates)) {
                        return $this->templates[$id];
                    }
                }
            }
        }
        $template = false;
        return $template;
    }

    /**
     * @param MPCETemplate|MPCETemplate[] $template
     */
    public function addTemplate($template) {
        if ($template instanceof MPCETemplate) {
            if ($template->isValid()) {
                if (!array_key_exists($template->getId(), $this->templates)) {
                    $this->templates[$template->getId()] = $template;
                }
            } else {
                if (!self::$isAjaxRequest) {
                    $template->showErrors();
                }
            }
        } elseif (is_array($template)) {
            if (!empty($template)) {
                foreach ($template as $t) {
                    if ($t instanceof MPCETemplate) {
                        if ($t->isValid()) {
                            if (!array_key_exists($t->getId(), $this->templates)) {
                                $this->templates[$t->getId()] = $t;
                            }
                        } else {
                            if (!self::$isAjaxRequest) {
                                $t->showErrors();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function removeTemplate($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->templates)) {
                        unset($this->templates[$id]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getData() {
        $library = array(
            'groups' => array(),
            'globalPredefinedClasses' => array(),
            'tinyMCEStyleFormats' => array(),
            'templates' => array(),
            'grid' => array()
        );
        foreach ($this->library as $group) {
            if (count($group->getObjects()) > 0) {
                uasort($group->objects, array(__CLASS__, 'positionCmp'));
                $library['groups'][$group->getId()] = $group;
            }
        }
        uasort($library['groups'], array(__CLASS__, 'positionCmp'));
        $library['globalPredefinedClasses'] = $this->globalPredefinedClasses;
        $library['tinyMCEStyleFormats'] = $this->tinyMCEStyleFormats;
        $library['templates'] = $this->templates;
        $library['grid'] = $this->gridObjects;
        return $library;
    }

    /**
     * @return array
     */
    public function getObjectsList() {
        $list = array();
        foreach ($this->library as $group){
            foreach ($group->getObjects() as $object) {
                $parameters = $object->getParameters();
                if (!empty($parameters)) {
                    foreach ($parameters as $key => $value) {
                        unset($parameters[$key]);
                        $parameters[$key] = array();
                    }
                }

                $list[$object->getId()] = array(
                    'parameters' => $parameters,
                    'group' => $group->getId()
                );
            }
        }
        return $list;
    }

    /**
     * @return array
     */
    public function getObjectsNames() {
        $names = array();
        foreach ($this->library as $group){
            foreach ($group->getObjects() as $object){
                $names[] = $object->getId();
            }
        }
        return $names;
    }

    /**
     * @static
     * @param MPCEObject $a
     * @param MPCEObject $b
     * @return int
     */
    /*
    public static function nameCmp(MPCEObject $a, MPCEObject $b) {
        return strcmp($a->getName(), $b->getName());
    }
    */

    /**
     * @param MPCEElement $a
     * @param MPCEElement $b
     * @return int
     */
    public function positionCmp(MPCEElement $a, MPCEElement $b) {
        $aPosition = $a->getPosition();
        $bPosition = $b->getPosition();
        if ($aPosition == $bPosition) {
            return 0;
        }
        return ($aPosition < $bPosition) ? -1 : 1;
    }

    /**
     * @return boolean
     */
    private function isAjaxRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ? true : false;
    }

    private function extendPredefinedWithGoogleFonts(&$predefined){
        global $motopressCESettings, $motopressCELang;
        $fontClasses = get_option('motopress_google_font_classes', array());
        if (!empty($fontClasses)) {
            $items = array();
            foreach ($fontClasses as $fontClassName => $fontClass) {
                $items[$fontClass['fullname']] = array(
                    'class' => $fontClass['fullname'],
                    'label' => $fontClassName,
                    'external' => $motopressCESettings['google_font_classes_dir_url'] . $fontClass['file']
                );
                if (!empty($fontClass['variants'])){
                    foreach($fontClass['variants'] as $variant){
                        $items[$fontClass['fullname'] . '-' . $variant] = array(
                            'class' => $fontClass['fullname'] . '-' . $variant,
                            'label' => $fontClassName . ' ' . $variant,
                            'external' => $motopressCESettings['google_font_classes_dir_url'] . $fontClass['file']
                        );
                    }
                }
            }
            $googleFontClasses = array(
                'label' => $motopressCELang->CEOptGoogleFontsSettings,
                'values' => $items
            );
            $predefined['google-font-classes'] = $googleFontClasses;
        }
    }

    public function getGridObjects(){
        return $this->gridObjects;
    }

    public function getGroupObjects(){
        $groupObjects = array();
        foreach($this->library as $group) {
            if (isset($group->objects)) {
                foreach ($group->objects as $objectName=>$object){
                    if (isset($object->parameters)) {
                        foreach($object->parameters as $parameter){
                            if ($parameter['type'] === 'group') {
                                $groupObjects[] = $objectName;
                            }
                        }
                    }
                }
            }
        }
        return $groupObjects;
    }

    public function setGrid($grid){

        if (is_array($grid)
            && isset($grid['row'])
            && isset($grid['span'])
        ){
            if (!isset($grid['row']['edgeclass'])) {
                $grid['row']['edgeclass'] = $grid['row']['class'];
            }
            // Backward compatibility
            if (!isset($grid['span']['custom_class_attr'])) {
                $grid['span']['custom_class_attr'] = 'mp_style_classes';
            }
            $grid['span']['minclass'] = $grid['span']['class'] . 1;
            $grid['span']['fullclass'] = $grid['span']['class'] . $grid['row']['col'];

            $this->gridObjects = $grid;
        }
    }
    public function setRow($rowArgs){
        $this->gridObjects['row'] = $rowArgs;
    }

    public function setSpan($spanArgs){
        $this->gridObjects['span'] =$spanArgs;
    }

    private function updateDeprecatedParams() {
        foreach ($this->library as $grp) {
            foreach ($grp->objects as $objName => $obj) {
                if (isset($obj->styles) && array_key_exists('mp_style_classes', $obj->styles)) {
                    if (!array_key_exists($objName, $this->deprecatedParameters)) {
                        $this->deprecatedParameters[$objName] = array();
                    }
                    if (!array_key_exists('custom_class', $this->deprecatedParameters[$objName])) {
                        $this->deprecatedParameters[$objName]['custom_class'] = array('prefix' => '');
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getIconClassList($useEmptyIcon = false) {
        $IconList = array(
            'fa fa-glass' => array(
                'class' => 'fa fa-glass',
                'label' => 'Glass'
            ),
            'fa fa-music' => array(
                'class' => 'fa fa-music',
                'label' => 'Music'
            ),
            'fa fa-search' => array(
                'class' => 'fa fa-search',
                'label' => 'Search'
            ),
            'fa fa-heart' => array(
                'class' => 'fa fa-heart',
                'label' => 'Heart'
            ),
            'fa fa-star' => array(
                'class' => 'fa fa-star',
                'label' => 'Star'
            ),
            'fa fa-star-o' => array(
                'class' => 'fa fa-star-o',
                'label' => 'Star O'
            ),
            'fa fa-user' => array(
                'class' => 'fa fa-user',
                'label' => 'User'
            ),
            'fa fa-film' => array(
                'class' => 'fa fa-film',
                'label' => 'Film'
            ),
            'fa fa-th-large' => array(
                'class' => 'fa fa-th-large',
                'label' => 'Th-large'
            ),
            'fa fa-th' => array(
                'class' => 'fa fa-th',
                'label' => 'Th'
            ),
            'fa fa-th-list' => array(
                'class' => 'fa fa-th-list',
                'label' => 'Th-list'
            ),
            'fa fa-check' => array(
                'class' => 'fa fa-check',
                'label' => 'Check'
            ),
            'fa fa-remove' => array(
                'class' => 'fa fa-remove',
                'label' => 'Remove Close Times'
            ),
            'fa fa-search-plus' => array(
                'class' => 'fa fa-search-plus',
                'label' => 'Search Plus'
            ),
            'fa fa-search-minus' => array(
                'class' => 'fa fa-search-minus',
                'label' => 'Search Minus'
            ),
            'fa fa-power-off' => array(
                'class' => 'fa fa-power-off',
                'label' => 'Power Off'
            ),
            'fa fa-signal' => array(
                'class' => 'fa fa-signal',
                'label' => 'Signal'
            ),
            'fa fa-gear' => array(
                'class' => 'fa fa-gear',
                'label' => 'Gear Cog'
            ),
            'fa fa-trash-o' => array(
                'class' => 'fa fa-trash-o',
                'label' => 'Trash O'
            ),
            'fa fa-home' => array(
                'class' => 'fa fa-home',
                'label' => 'Home'
            ),
            'fa fa-file-o' => array(
                'class' => 'fa fa-file-o',
                'label' => 'File O'
            ),
            'fa fa-clock-o' => array(
                'class' => 'fa fa-clock-o',
                'label' => 'Clock O'
            ),
            'fa fa-road' => array(
                'class' => 'fa fa-road',
                'label' => 'Road'
            ),
            'fa fa-download' => array(
                'class' => 'fa fa-download',
                'label' => 'Download'
            ),
            'fa fa-arrow-circle-o-down' => array(
                'class' => 'fa fa-arrow-circle-o-down',
                'label' => 'Arrow Circle O Down'
            ),
            'fa fa-arrow-circle-o-up' => array(
                'class' => 'fa fa-arrow-circle-o-up',
                'label' => 'Arrow Circle O Up'
            ),
            'fa fa-inbox' => array(
                'class' => 'fa fa-inbox',
                'label' => 'Inbox'
            ),
            'fa fa-play-circle-o' => array(
                'class' => 'fa fa-circle-o',
                'label' => 'Circle O'
            ),
            'fa fa-rotate-right' => array(
                'class' => 'fa fa-rotate-right',
                'label' => 'Rotate Right, Repeat'
            ),
            'fa fa-refresh' => array(
                'class' => 'fa fa-refresh',
                'label' => 'Refresh'
            ),
            'fa fa-list-alt' => array(
                'class' => 'fa fa-list-alt',
                'label' => 'List Alt'
            ),
            'fa fa-lock' => array(
                'class' => 'fa fa-lock',
                'label' => 'Lock'
            ),
            'fa fa-flag' => array(
                'class' => 'fa fa-flag',
                'label' => 'Flag'
            ),
            'fa fa-headphones' => array(
                'class' => 'fa fa-headphones',
                'label' => 'Headphones'
            ),
            'fa fa-volume-off' => array(
                'class' => 'fa fa-volume-off',
                'label' => 'Volume Off'
            ),
            'fa fa-volume-down' => array(
                'class' => 'fa fa-volume-down',
                'label' => 'Volume Down'
            ),
            'fa fa-volume-up' => array(
                'class' => 'fa fa-volume-up',
                'label' => 'Volume Up'
            ),
            'fa fa-qrcode' => array(
                'class' => 'fa fa-qrcode',
                'label' => 'QR Code'
            ),
            'fa fa-barcode' => array(
                'class' => 'fa fa-barcode',
                'label' => 'Barcode'
            ),
            'fa fa-tag' => array(
                'class' => 'fa fa-tag',
                'label' => 'Tag'
            ),
            'fa fa-tags' => array(
                'class' => 'fa fa-tags',
                'label' => 'Tags'
            ),
            'fa fa-book' => array(
                'class' => 'fa fa-book',
                'label' => 'Book'
            ),
            'fa fa-bookmark' => array(
                'class' => 'fa fa-bookmark',
                'label' => 'Bookmark'
            ),
            'fa fa-print' => array(
                'class' => 'fa fa-print',
                'label' => 'Print'
            ),
            'fa fa-camera' => array(
                'class' => 'fa fa-camera',
                'label' => 'Camera'
            ),
            'fa fa-font' => array(
                'class' => 'fa fa-font',
                'label' => 'Font'
            ),
            'fa fa-bold' => array(
                'class' => 'fa fa-bold',
                'label' => 'Bold'
            ),
            'fa fa-italic' => array(
                'class' => 'fa fa-italic',
                'label' => 'Italic'
            ),
            'fa fa-text-height' => array(
                'class' => 'fa fa-text-height',
                'label' => 'Text Height'
            ),
            'fa fa-text-width' => array(
                'class' => 'fa fa-text-width',
                'label' => 'Width'
            ),
            'fa fa-align-left' => array(
                'class' => 'fa fa-align-left',
                'label' => 'Align Left'
            ),
            'fa fa-align-center' => array(
                'class' => 'fa fa-align-center',
                'label' => 'Align Center'
            ),
            'fa fa-align-right' => array(
                'class' => 'fa fa-align-right',
                'label' => 'Align Right'
            ),
            'fa fa-align-justify' => array(
                'class' => 'fa fa-align-justify',
                'label' => 'Align Justify'
            ),
            'fa fa-list' => array(
                'class' => 'fa fa-list',
                'label' => 'List'
            ),
            'fa fa-dedent' => array(
                'class' => 'fa fa-dedent',
                'label' => 'Dedent, Outdent'
            ),
            'fa fa-indent' => array(
                'class' => 'fa fa-indent',
                'label' => 'Indent'
            ),
            'fa fa-video-camera' => array(
                'class' => 'fa fa-video-camera',
                'label' => 'Video Camera'
            ),
            'fa fa-image' => array(
                'class' => 'fa fa-image',
                'label' => 'Photo, Image, Picture O'
            ),
            'fa fa-pencil' => array(
                'class' => 'fa fa-pencil',
                'label' => 'Pencil'
            ),
            'fa fa-map-marker' => array(
                'class' => 'fa fa-map-marker',
                'label' => 'Map Marker'
            ),
            'fa fa-adjust' => array(
                'class' => 'fa fa-adjust',
                'label' => 'Adjust'
            ),
            'fa fa-tint' => array(
                'class' => 'fa fa-tint',
                'label' => 'Tint'
            ),
            'fa fa-edit' => array(
                'class' => 'fa fa-edit',
                'label' => 'Edit, Pencil Square O'
            ),
            'fa fa-share-square-o' => array(
                'class' => 'fa fa-share-square-o',
                'label' => 'Share Square O'
            ),
            'fa fa-check-square-o' => array(
                'class' => 'fa fa-check-square-o',
                'label' => 'Check Square O'
            ),
            'fa fa-arrows' => array(
                'class' => 'fa fa-arrows',
                'label' => 'Arrows'
            ),
            'fa fa-step-backward' => array(
                'class' => 'fa fa-step-backward',
                'label' => 'Step Backward'
            ),
            'fa fa-fast-backward' => array(
                'class' => 'fa fa-fast-backward',
                'label' => 'Fast Backward'
            ),
            'fa fa-backward' => array(
                'class' => 'fa fa-backward',
                'label' => 'Backward'
            ),
            'fa fa-play' => array(
                'class' => 'fa fa-play',
                'label' => 'Play'
            ),
            'fa fa-pause' => array(
                'class' => 'fa fa-pause',
                'label' => 'Pause'
            ),
            'fa fa-stop' => array(
                'class' => 'fa fa-stop',
                'label' => 'Stop'
            ),
            'fa fa-forward' => array(
                'class' => 'fa fa-forward',
                'label' => 'Forward'
            ),
            'fa fa-fast-forward' => array(
                'class' => 'fa fa-fast-forward',
                'label' => 'Fast Forward'
            ),
            'fa fa-step-forward' => array(
                'class' => 'fa fa-step-forward',
                'label' => 'Step Forward'
            ),
            'fa fa-eject' => array(
                'class' => 'fa fa-eject',
                'label' => 'Eject'
            ),
            'fa fa-chevron-left' => array(
                'class' => 'fa fa-chevron-left',
                'label' => 'Chevron Left'
            ),
            'fa fa-chevron-right' => array(
                'class' => 'fa fa-chevron-right',
                'label' => 'Chevron Right'
            ),
            'fa fa-plus-circle' => array(
                'class' => 'fa fa-plus-circle',
                'label' => 'Plus Circle'
            ),
            'fa fa-minus-circle' => array(
                'class' => 'fa fa-minus-circle',
                'label' => 'Minus Circle'
            ),
            'fa fa-times-circle' => array(
                'class' => 'fa fa-times-circle',
                'label' => 'Times Circle'
            ),
            'fa fa-check-circle' => array(
                'class' => 'fa fa-check-circle',
                'label' => 'Check Circle'
            ),
            'fa fa-question-circle' => array(
                'class' => 'fa fa-question-circle',
                'label' => 'Question Circle'
            ),
            'fa fa-info-circle' => array(
                'class' => 'fa fa-info-circle',
                'label' => 'Info Circle'
            ),
            'fa fa-crosshairs' => array(
                'class' => 'fa fa-crosshairs',
                'label' => 'Crosshairs'
            ),
            'fa fa-times-circle-o' => array(
                'class' => 'fa fa-times-circle-o',
                'label' => 'Times Circle O'
            ),
            'fa fa-check-circle-o' => array(
                'class' => 'fa fa-check-circle-o',
                'label' => 'Check Circle O'
            ),
            'fa fa-ban' => array(
                'class' => 'fa fa-ban',
                'label' => 'Ban'
            ),
            'fa fa-arrow-left' => array(
                'class' => 'fa fa-arrow-left',
                'label' => 'Arrow Left'
            ),
            'fa fa-arrow-right' => array(
                'class' => 'fa fa-arrow-right',
                'label' => 'Arrow Right'
            ),
            'fa fa-arrow-up' => array(
                'class' => 'fa fa-arrow-up',
                'label' => 'Arrow Up'
            ),
            'fa fa-arrow-down' => array(
                'class' => 'fa fa-arrow-down',
                'label' => 'Arrow Down'
            ),
            'fa fa-share' => array(
                'class' => 'fa fa-share',
                'label' => 'Mail Forward, Share'
            ),
            'fa fa-expand' => array(
                'class' => 'fa fa-expand',
                'label' => 'Expand'
            ),
            'fa fa-compress' => array(
                'class' => 'fa fa-compress',
                'label' => 'Compress'
            ),
            'fa fa-plus' => array(
                'class' => 'fa fa-plus',
                'label' => 'Plus'
            ),
            'fa fa-minus' => array(
                'class' => 'fa fa-minus',
                'label' => 'Minus'
            ),
            'fa fa-asterisk' => array(
                'class' => 'fa fa-asterisk',
                'label' => 'Asterisk'
            ),
            'fa fa-exclamation-circle' => array(
                'class' => 'fa fa-exclamation-circle',
                'label' => 'Exclamation Circle'
            ),
            'fa fa-gift' => array(
                'class' => 'fa fa-gift',
                'label' => 'Gift'
            ),
            'fa fa-leaf' => array(
                'class' => 'fa fa-leaf',
                'label' => 'Leaf'
            ),
            'fa fa-fire' => array(
                'class' => 'fa fa-fire',
                'label' => 'Fire'
            ),
            'fa fa-eye' => array(
                'class' => 'fa fa-eye',
                'label' => 'Eye'
            ),
            'fa fa-eye-slash' => array(
                'class' => 'fa fa-eye-slash',
                'label' => 'Eye Slash'
            ),
            'fa fa-warning' => array(
                'class' => 'fa fa-warning',
                'label' => 'Warning, Exclamation, Triangle'
            ),
            'fa fa-plane' => array(
                'class' => 'fa fa-plane',
                'label' => 'Plane'
            ),
            'fa fa-calendar' => array(
                'class' => 'fa fa-calendar',
                'label' => 'Calendar'
            ),
            'fa fa-random' => array(
                'class' => 'fa fa-random',
                'label' => 'Random'
            ),
            'fa fa-comment' => array(
                'class' => 'fa fa-comment',
                'label' => 'Comment'
            ),
            'fa fa-magnet' => array(
                'class' => 'fa fa-magnet',
                'label' => 'Magnet'
            ),
            'fa fa-chevron-up' => array(
                'class' => 'fa fa-chevron-up',
                'label' => 'Chevron Up'
            ),
            'fa fa-chevron-down' => array(
                'class' => 'fa fa-chevron-down',
                'label' => 'Chevron Down'
            ),
            'fa fa-retweet' => array(
                'class' => 'fa fa-retweet',
                'label' => 'Retweet'
            ),
            'fa fa-shopping-cart' => array(
                'class' => 'fa fa-shopping-cart',
                'label' => 'Shopping Cart'
            ),
            'fa fa-folder' => array(
                'class' => 'fa fa-folder',
                'label' => 'Folder'
            ),
            'fa fa-folder-open' => array(
                'class' => 'fa fa-folder-open',
                'label' => 'Folder Open'
            ),
            'fa fa-arrows-v' => array(
                'class' => 'fa fa-arrows-v',
                'label' => 'Arrows Vertical'
            ),
            'fa fa-arrows-h' => array(
                'class' => 'fa fa-arrows-h',
                'label' => 'Arrows Horizontal'
            ),
            'fa fa-bar-chart' => array(
                'class' => 'fa fa-bar-chart',
                'label' => 'Chart O'
            ),
            'fa fa-twitter-square' => array(
                'class' => 'fa fa-twitter-square',
                'label' => 'Twitter Square'
            ),
            'fa fa-facebook-square' => array(
                'class' => 'fa fa-facebook-square',
                'label' => 'Facebook Square'
            ),
            'fa fa-camera-retro' => array(
                'class' => 'fa fa-camera-retro',
                'label' => 'Camera Retro'
            ),
            'fa fa-key' => array(
                'class' => 'fa fa-key',
                'label' => 'Key'
            ),
            'fa fa-gears' => array(
                'class' => 'fa fa-gears',
                'label' => 'Gears Cogs'
            ),
            'fa fa-comments' => array(
                'class' => 'fa fa-comments',
                'label' => 'Comments'
            ),
            'fa fa-thumbs-o-up' => array(
                'class' => 'fa fa-thumbs-o-up',
                'label' => 'Thumbs O Up'
            ),
            'fa fa-thumbs-o-down' => array(
                'class' => 'fa fa-thumbs-o-down',
                'label' => 'Thumbs O Down'
            ),
            'fa fa-star-half' => array(
                'class' => 'fa fa-star-half',
                'label' => 'Star Half'
            ),
            'fa fa-heart-o' => array(
                'class' => 'fa fa-heart-o',
                'label' => 'Heart O'
            ),
            'fa fa-sign-out' => array(
                'class' => 'fa fa-sign-out',
                'label' => 'Sign Out'
            ),
            'fa fa-linkedin-square' => array(
                'class' => 'fa fa-linkedin-square',
                'label' => 'Linkedin Square'
            ),
            'fa fa-thumb-tack' => array(
                'class' => 'fa fa-thumb-tack',
                'label' => 'Thumb Tack'
            ),
            'fa fa-external-link' => array(
                'class' => 'fa fa-external-link',
                'label' => 'External Link'
            ),
            'fa fa-sign-in' => array(
                'class' => 'fa fa-sign-in',
                'label' => 'Sign In'
            ),
            'fa fa-trophy' => array(
                'class' => 'fa fa-trophy',
                'label' => 'Trophy'
            ),
            'fa fa-github-square' => array(
                'class' => 'fa fa-github-square',
                'label' => 'Github Square'
            ),
            'fa fa-upload' => array(
                'class' => 'fa fa-upload',
                'label' => 'Upload'
            ),
            'fa fa-lemon-o' => array(
                'class' => 'fa fa-lemon-o',
                'label' => 'Lemon O'
            ),
            'fa fa-phone' => array(
                'class' => 'fa fa-phone',
                'label' => 'Phone'
            ),
            'fa fa-square-o' => array(
                'class' => 'fa fa-square-o',
                'label' => 'Square O'
            ),
            'fa fa-bookmark-o' => array(
                'class' => 'fa fa-bookmark-o',
                'label' => 'Bookmark O'
            ),
            'fa fa-phone-square' => array(
                'class' => 'fa fa-phone-square',
                'label' => 'Phone Square'
            ),
            'fa fa-twitter' => array(
                'class' => 'fa fa-twitter',
                'label' => 'Twitter'
            ),
            'fa fa-facebook' => array(
                'class' => 'fa fa-facebook',
                'label' => 'Facebook'
            ),
            'fa fa-github' => array(
                'class' => 'fa fa-github',
                'label' => 'Github'
            ),
            'fa fa-unlock' => array(
                'class' => 'fa fa-unlock',
                'label' => 'Unlock'
            ),
            'fa fa-credit-card' => array(
                'class' => 'fa fa-credit-card',
                'label' => 'Credit Card'
            ),
            'fa fa-rss' => array(
                'class' => 'fa fa-rss',
                'label' => 'RSS'
            ),
            'fa fa-hdd-o' => array(
                'class' => 'fa fa-hdd-o',
                'label' => 'HDD O'
            ),
            'fa fa-bullhorn' => array(
                'class' => 'fa fa-bullhorn',
                'label' => 'Bullhorn'
            ),
            'fa fa-bell' => array(
                'class' => 'fa fa-bell',
                'label' => 'Bell'
            ),
            'fa fa-certificate' => array(
                'class' => 'fa fa-certificate',
                'label' => 'Certificate'
            ),
            'fa fa-hand-o-right' => array(
                'class' => 'fa fa-hand-o-right',
                'label' => 'Hand O Right'
            ),
            'fa fa-hand-o-left' => array(
                'class' => 'fa fa-hand-o-left',
                'label' => 'Hand O Left'
            ),
            'fa fa-hand-o-up' => array(
                'class' => 'fa fa-hand-o-up',
                'label' => 'Hand O Up'
            ),
            'fa fa-hand-o-down' => array(
                'class' => 'fa fa-hand-o-down',
                'label' => 'Hand O Down'
            ),
            'fa fa-arrow-circle-left' => array(
                'class' => 'fa fa-arrow-circle-left',
                'label' => 'Arrow Circle Left'
            ),
            'fa fa-arrow-circle-right' => array(
                'class' => 'fa fa-arrow-circle-right',
                'label' => 'Arrow Circle Right'
            ),
            'fa fa-arrow-circle-up' => array(
                'class' => 'fa fa-arrow-circle-up',
                'label' => 'Arrow Circle Up'
            ),
            'fa fa-arrow-circle-down' => array(
                'class' => 'fa fa-arrow-circle-down',
                'label' => 'Arrow Circle Down'
            ),
            'fa fa-globe' => array(
                'class' => 'fa fa-globe',
                'label' => 'Globe'
            ),
            'fa fa-wrench' => array(
                'class' => 'fa fa-wrench',
                'label' => 'Wrench'
            ),
            'fa fa-tasks' => array(
                'class' => 'fa fa-tasks',
                'label' => 'Tasks'
            ),
            'fa fa-filter' => array(
                'class' => 'fa fa-filter',
                'label' => 'Filter'
            ),
            'fa fa-briefcase' => array(
                'class' => 'fa fa-briefcase',
                'label' => 'Briefcase'
            ),
            'fa fa-arrows-alt' => array(
                'class' => 'fa fa-arrows-alt',
                'label' => 'Arrows Alt'
            ),
            'fa fa-users' => array(
                'class' => 'fa fa-users',
                'label' => 'Group, Users'
            ),
            'fa fa-link' => array(
                'class' => 'fa fa-link',
                'label' => 'Link, Chain'
            ),
            'fa fa-cloud' => array(
                'class' => 'fa fa-cloud',
                'label' => 'Cloud'
            ),
            'fa fa-flask' => array(
                'class' => 'fa fa-flask',
                'label' => 'Flask'
            ),
            'fa fa-cut' => array(
                'class' => 'fa fa-cut',
                'label' => 'Scissors, Cut'
            ),
            'fa fa-copy' => array(
                'class' => 'fa fa-copy',
                'label' => 'Files, Copy'
            ),
            'fa fa-paperclip' => array(
                'class' => 'fa fa-paperclip',
                'label' => 'Paperclip'
            ),
            'fa fa-save' => array(
                'class' => 'fa fa-save',
                'label' => 'Floppy O, Save'
            ),
            'fa fa-square' => array(
                'class' => 'fa fa-square',
                'label' => 'Square'
            ),
            'fa fa-navicon' => array(
                'class' => 'fa fa-navicon',
                'label' => 'Navicon, Reorder, Bars'
            ),
            'fa fa-list-ul' => array(
                'class' => 'fa fa-list-ul',
                'label' => 'List Ul'
            ),
            'fa fa-list-ol' => array(
                'class' => 'fa fa-list-ol',
                'label' => 'List Ol'
            ),
            'fa fa-strikethrough' => array(
                'class' => 'fa fa-strikethrough',
                'label' => 'Strikethrough'
            ),
            'fa fa-underline' => array(
                'class' => 'fa fa-underline',
                'label' => 'Underline'
            ),
            'fa fa-table' => array(
                'class' => 'fa fa-table',
                'label' => 'Table'
            ),
            'fa fa-magic' => array(
                'class' => 'fa fa-magic',
                'label' => 'Magic'
            ),
            'fa fa-truck' => array(
                'class' => 'fa fa-truck',
                'label' => 'Truck'
            ),
            'fa fa-pinterest' => array(
                'class' => 'fa fa-pinterest',
                'label' => 'Pinterest'
            ),
            'fa fa-pinterest-square' => array(
                'class' => 'fa fa-pinterest-square',
                'label' => 'Pinterest Square'
            ),
            'fa fa-google-plus-square' => array(
                'class' => 'fa fa-google-plus-square',
                'label' => 'Google Plus Square'
            ),
            'fa fa-google-plus' => array(
                'class' => 'fa fa-google-plus',
                'label' => 'Google Plus'
            ),
            'fa fa-money' => array(
                'class' => 'fa fa-money',
                'label' => 'Money'
            ),
            'fa fa-caret-down' => array(
                'class' => 'fa fa-caret-down',
                'label' => 'Caret Down'
            ),
            'fa fa-caret-up' => array(
                'class' => 'fa fa-caret-up',
                'label' => 'Caret Up'
            ),
            'fa fa-caret-left' => array(
                'class' => 'fa fa-caret-left',
                'label' => 'Caret Left'
            ),
            'fa fa-caret-right' => array(
                'class' => 'fa fa-caret-right',
                'label' => 'Caret Right'
            ),
            'fa fa-columns' => array(
                'class' => 'fa fa-columns',
                'label' => 'Columns'
            ),
            'fa fa-sort' => array(
                'class' => 'fa fa-sort',
                'label' => 'Sort Unsorted'
            ),
            'fa fa-sort-down' => array(
                'class' => 'fa fa-sort-down',
                'label' => 'Sort Down, Sort Desc'
            ),
            'fa fa-sort-up' => array(
                'class' => 'fa fa-sort-up',
                'label' => 'Sort Up, Sort Asc'
            ),
            'fa fa-envelope' => array(
                'class' => 'fa fa-envelope',
                'label' => 'Envelope'
            ),
            'fa fa-linkedin' => array(
                'class' => 'fa fa-linkedin',
                'label' => 'Linkedin'
            ),
            'fa fa-undo' => array(
                'class' => 'fa fa-undo',
                'label' => 'Undo, Rotate Left'
            ),
            'fa fa-legal' => array(
                'class' => 'fa fa-legal',
                'label' => 'Legal Gavel'
            ),
            'fa fa-dashboard' => array(
                'class' => 'fa fa-dashboard',
                'label' => 'Dashboard, Tachometer'
            ),
            'fa fa-comment-o' => array(
                'class' => 'fa fa-comment-o',
                'label' => 'Comment O'
            ),
            'fa fa-comments-o' => array(
                'class' => 'fa fa-comments-o',
                'label' => 'Comments O'
            ),
            'fa fa-flash' => array(
                'class' => 'fa fa-flash',
                'label' => 'Flash, Bolt'
            ),
            'fa fa-sitemap' => array(
                'class' => 'fa fa-sitemap',
                'label' => 'Sitemap'
            ),
            'fa fa-umbrella' => array(
                'class' => 'fa fa-umbrella',
                'label' => 'Umbrella'
            ),
            'fa fa-paste' => array(
                'class' => 'fa fa-paste',
                'label' => 'Paste, Clipboard'
            ),
            'fa fa-lightbulb-o' => array(
                'class' => 'fa fa-lightbulb-o',
                'label' => 'Lightbulb O'
            ),
            'fa fa-exchange' => array(
                'class' => 'fa fa-exchange',
                'label' => 'Exchange'
            ),
            'fa fa-cloud-download' => array(
                'class' => 'fa fa-cloud-download',
                'label' => 'Cloud Download'
            ),
            'fa fa-cloud-upload' => array(
                'class' => 'fa fa-cloud-upload',
                'label' => 'Cloud Upload'
            ),
            'fa fa-user-md' => array(
                'class' => 'fa fa-user-md',
                'label' => 'User Md'
            ),
            'fa fa-stethoscope' => array(
                'class' => 'fa fa-stethoscope',
                'label' => 'Stethoscope'
            ),
            'fa fa-suitcase' => array(
                'class' => 'fa fa-suitcase',
                'label' => 'Suitcase'
            ),
            'fa fa-bell-o' => array(
                'class' => 'fa fa-bell-o',
                'label' => 'Bell O'
            ),
            'fa fa-coffee' => array(
                'class' => 'fa fa-coffee',
                'label' => 'Coffee'
            ),
            'fa fa-cutlery' => array(
                'class' => 'fa fa-cutlery',
                'label' => 'Cutlery'
            ),
            'fa fa-file-text-o' => array(
                'class' => 'fa fa-file-text-o',
                'label' => 'File Text O'
            ),
            'fa fa-building-o' => array(
                'class' => 'fa fa-building-o',
                'label' => 'Building O'
            ),
            'fa fa-hospital-o' => array(
                'class' => 'fa fa-hospital-o',
                'label' => 'Hospital O'
            ),
            'fa fa-ambulance' => array(
                'class' => 'fa fa-ambulance',
                'label' => 'Ambulance'
            ),
            'fa fa-medkit' => array(
                'class' => 'fa fa-medkit',
                'label' => 'Medkit'
            ),
            'fa fa-fighter-jet' => array(
                'class' => 'fa fa-fighter-jet',
                'label' => 'Fighter Jet'
            ),
            'fa fa-beer' => array(
                'class' => 'fa fa-beer',
                'label' => 'Beer'
            ),
            'fa fa-h-square' => array(
                'class' => 'fa fa-h-square',
                'label' => 'H Square'
            ),
            'fa fa-plus-square' => array(
                'class' => 'fa fa-plus-square',
                'label' => 'Plus Square'
            ),
            'fa fa-angle-double-left' => array(
                'class' => 'fa fa-angle-double-left',
                'label' => 'Angle Double Left'
            ),
            'fa fa-angle-double-right' => array(
                'class' => 'fa fa-angle-double-right',
                'label' => 'Angle Double Right'
            ),
            'fa fa-angle-double-up' => array(
                'class' => 'fa fa-angle-double-up',
                'label' => 'Angle Double Up'
            ),
            'fa fa-angle-double-down' => array(
                'class' => 'fa fa-angle-double-down',
                'label' => 'Angle Double Down'
            ),
            'fa fa-angle-left' => array(
                'class' => 'fa fa-angle-left',
                'label' => 'Angle Left'
            ),
            'fa fa-angle-right' => array(
                'class' => 'fa fa-angle-right',
                'label' => 'Angle Right'
            ),
            'fa fa-angle-up' => array(
                'class' => 'fa fa-angle-up',
                'label' => 'Angle Up'
            ),
            'fa fa-angle-down' => array(
                'class' => 'fa fa-angle-down',
                'label' => 'Angle Down'
            ),
            'fa fa-desktop' => array(
                'class' => 'fa fa-desktop',
                'label' => 'Desktop'
            ),
            'fa fa-laptop' => array(
                'class' => 'fa fa-laptop',
                'label' => 'Laptop'
            ),
            'fa fa-tablet' => array(
                'class' => 'fa fa-tablet',
                'label' => 'Tablet'
            ),
            'fa fa-mobile' => array(
                'class' => 'fa fa-mobile',
                'label' => 'Mobile Phone'
            ),
            'fa fa-circle-o' => array(
                'class' => 'fa fa-circle-o',
                'label' => 'Circle O'
            ),
            'fa fa-quote-left' => array(
                'class' => 'fa fa-quote-left',
                'label' => 'Quote Left'
            ),
            'fa fa-quote-right' => array(
                'class' => 'fa fa-quote-right',
                'label' => 'Quote Right'
            ),
            'fa fa-spinner' => array(
                'class' => 'fa fa-spinner',
                'label' => 'Spinner'
            ),
            'fa fa-circle' => array(
                'class' => 'fa fa-circle',
                'label' => 'Circle'
            ),
            'fa fa-reply' => array(
                'class' => 'fa fa-reply',
                'label' => 'Mail Reply'
            ),
            'fa fa-github-alt' => array(
                'class' => 'fa fa-github-alt',
                'label' => 'Github Alt'
            ),
            'fa fa-folder-o' => array(
                'class' => 'fa fa-folder-o',
                'label' => 'Folder O'
            ),
            'fa fa-folder-open-o' => array(
                'class' => 'fa fa-folder-open-o',
                'label' => 'Folder Open O'
            ),
            'fa fa-smile-o' => array(
                'class' => 'fa fa-smile-o',
                'label' => 'Smile O'
            ),
            'fa fa-frown-o' => array(
                'class' => 'fa fa-frown-o',
                'label' => 'Frown O'
            ),
            'fa fa-meh-o' => array(
                'class' => 'fa fa-meh-o',
                'label' => 'Meh O'
            ),
            'fa fa-gamepad' => array(
                'class' => 'fa fa-gamepad',
                'label' => 'Gamepad'
            ),
            'fa fa-keyboard-o' => array(
                'class' => 'fa fa-keyboard-o',
                'label' => 'Keyboard O'
            ),
            'fa fa-flag-o' => array(
                'class' => 'fa fa-flag-o',
                'label' => 'Flag O'
            ),
            'fa fa-flag-checkered' => array(
                'class' => 'fa fa-flag-checkered',
                'label' => 'Flag Checkered'
            ),
            'fa fa-terminal' => array(
                'class' => 'fa fa-terminal',
                'label' => 'Terminal'
            ),
            'fa fa-code' => array(
                'class' => 'fa fa-code',
                'label' => 'Code'
            ),
            'fa fa-reply-all' => array(
                'class' => 'fa fa-reply-all',
                'label' => 'Mail Reply All'
            ),
            'fa fa-star-half-full' => array(
                'class' => 'fa fa-star-half-full',
                'label' => 'Star Half O, Star Half Empty, Star Half Full'
            ),
            'fa fa-location-arrow' => array(
                'class' => 'fa fa-location-arrow',
                'label' => 'Location Arrow'
            ),
            'fa fa-crop' => array(
                'class' => 'fa fa-crop',
                'label' => 'Crop'
            ),
            'fa fa-code-fork' => array(
                'class' => 'fa fa-code-fork',
                'label' => 'Code Fork'
            ),
            'fa fa-unlink' => array(
                'class' => 'fa fa-unlink',
                'label' => 'Unlink, Chain Broken'
            ),
            'fa fa-question' => array(
                'class' => 'fa fa-question',
                'label' => 'Question'
            ),
            'fa fa-info' => array(
                'class' => 'fa fa-info',
                'label' => 'Info'
            ),
            'fa fa-exclamation' => array(
                'class' => 'fa fa-exclamation',
                'label' => 'Exclamation'
            ),
            'fa fa-superscript' => array(
                'class' => 'fa fa-superscript',
                'label' => 'Superscript'
            ),
            'fa fa-subscript' => array(
                'class' => 'fa fa-subscript',
                'label' => 'Subscript'
            ),
            'fa fa-eraser' => array(
                'class' => 'fa fa-eraser',
                'label' => 'Eraser'
            ),
            'fa fa-puzzle-piece' => array(
                'class' => 'fa fa-puzzle-piece',
                'label' => 'Puzzle Piece'
            ),
            'fa fa-microphone' => array(
                'class' => 'fa fa-microphone',
                'label' => 'Microphone'
            ),
            'fa fa-microphone-slash' => array(
                'class' => 'fa fa-microphone-slash',
                'label' => 'Microphone Slash'
            ),
            'fa fa-shield' => array(
                'class' => 'fa fa-shield',
                'label' => 'Shield'
            ),
            'fa fa-calendar-o' => array(
                'class' => 'fa fa-calendar-o',
                'label' => 'Calendar O'
            ),
            'fa fa-fire-extinguisher' => array(
                'class' => 'fa fa-fire-extinguisher',
                'label' => 'Fire Extinguisher'
            ),
            'fa fa-rocket' => array(
                'class' => 'fa fa-rocket',
                'label' => 'Rocket'
            ),
            'fa fa-maxcdn' => array(
                'class' => 'fa fa-maxcdn',
                'label' => 'MaxCDN'
            ),
            'fa fa-chevron-circle-left' => array(
                'class' => 'fa fa-chevron-circle-left',
                'label' => 'Chevron Circle Left'
            ),
            'fa fa-chevron-circle-right' => array(
                'class' => 'fa fa-chevron-circle-right',
                'label' => 'Chevron Circle Right'
            ),
            'fa fa-chevron-circle-up' => array(
                'class' => 'fa fa-chevron-circle-up',
                'label' => 'Chevron Circle Up'
            ),
            'fa fa-chevron-circle-down' => array(
                'class' => 'fa fa-chevron-circle-down',
                'label' => 'Chevron Circle Down'
            ),
            'fa fa-html5' => array(
                'class' => 'fa fa-html5',
                'label' => 'HTML5'
            ),
            'fa fa-css3' => array(
                'class' => 'fa fa-css3',
                'label' => 'CSS3'
            ),
            'fa fa-anchor' => array(
                'class' => 'fa fa-anchor',
                'label' => 'Anchor'
            ),
            'fa fa-unlock-alt' => array(
                'class' => 'fa fa-unlock-alt',
                'label' => 'Unlock Alt'
            ),
            'fa fa-bullseye' => array(
                'class' => 'fa fa-bullseye',
                'label' => 'Bullseye'
            ),
            'fa fa-ellipsis-h' => array(
                'class' => 'fa fa-ellipsis-h',
                'label' => 'Ellipsis Horizontal'
            ),
            'fa fa-ellipsis-v' => array(
                'class' => 'fa fa-ellipsis-v',
                'label' => 'Ellipsis Vertical'
            ),
            'fa fa-rss-square' => array(
                'class' => 'fa fa-rss-square',
                'label' => 'RSS Square'
            ),
            'fa fa-play-circle' => array(
                'class' => 'fa fa-play-circle',
                'label' => 'Play Circle'
            ),
            'fa fa-ticket' => array(
                'class' => 'fa fa-ticket',
                'label' => 'Ticket'
            ),
            'fa fa-minus-square' => array(
                'class' => 'fa fa-minus-square',
                'label' => 'Minus Square'
            ),
            'fa fa-minus-square-o' => array(
                'class' => 'fa fa-minus-square-o',
                'label' => 'Minus Square O'
            ),
            'fa fa-level-up' => array(
                'class' => 'fa fa-level-up',
                'label' => 'Level Up'
            ),
            'fa fa-level-down' => array(
                'class' => 'fa fa-level-down',
                'label' => 'Level Down'
            ),
            'fa fa-check-square' => array(
                'class' => 'fa fa-check-square',
                'label' => 'Check Square'
            ),
            'fa fa-pencil-square' => array(
                'class' => 'fa fa-pencil-square',
                'label' => 'Pencil Square'
            ),
            'fa fa-external-link-square' => array(
                'class' => 'fa fa-external-link-square',
                'label' => 'External Link Square'
            ),
            'fa fa-share-square' => array(
                'class' => 'fa fa-share-square',
                'label' => 'Share Square'
            ),
            'fa fa-compass' => array(
                'class' => 'fa fa-compass',
                'label' => 'Compass'
            ),
            'fa fa-toggle-down' => array(
                'class' => 'fa fa-toggle-down',
                'label' => 'Toggle Down, Caret Square O Down'
            ),
            'fa fa-toggle-up' => array(
                'class' => 'fa fa-toggle-up',
                'label' => 'Toggle Up, Caret Square O Up'
            ),
            'fa fa-toggle-right' => array(
                'class' => 'fa fa-toggle-right',
                'label' => 'Toggle Right, Caret Square O Right'
            ),
            'fa fa-euro' => array(
                'class' => 'fa fa-euro',
                'label' => 'Euro, Eur'
            ),
            'fa fa-gbp' => array(
                'class' => 'fa fa-gbp',
                'label' => 'GBP'
            ),
            'fa fa-usd' => array(
                'class' => 'fa fa-usd',
                'label' => 'USD, Dollar'
            ),
            'fa fa-rupee' => array(
                'class' => 'fa fa-rupee',
                'label' => 'Rupee, INR'
            ),
            'fa fa-yen' => array(
                'class' => 'fa fa-yen',
                'label' => 'CNY, RMB, Yen, JPY'
            ),
            'fa fa-ruble' => array(
                'class' => 'fa fa-ruble',
                'label' => 'Ruble, Rouble, Rub'
            ),
            'fa fa-won' => array(
                'class' => 'fa fa-won',
                'label' => 'Won, Krw'
            ),
            'fa fa-bitcoin' => array(
                'class' => 'fa fa-bitcoin',
                'label' => 'Bitcoin, BTC'
            ),
            'fa fa-file' => array(
                'class' => 'fa fa-file',
                'label' => 'File'
            ),
            'fa fa-file-text' => array(
                'class' => 'fa fa-file-text',
                'label' => 'File Text'
            ),
            'fa fa-sort-alpha-asc' => array(
                'class' => 'fa fa-sort-alpha-asc',
                'label' => 'Sort Alpha ASC'
            ),
            'fa fa-sort-alpha-desc' => array(
                'class' => 'fa fa-sort-alpha-desc',
                'label' => 'Sort Alpha DESC'
            ),
            'fa fa-sort-amount-asc' => array(
                'class' => 'fa fa-sort-amount-asc',
                'label' => 'Sort Amount ASC'
            ),
            'fa fa-sort-amount-desc' => array(
                'class' => 'fa fa-sort-amount-desc',
                'label' => 'Sort Amount Desc'
            ),
            'fa fa-sort-numeric-asc' => array(
                'class' => 'fa fa-sort-numeric-asc',
                'label' => 'Sort Numeric ASC'
            ),
            'fa fa-sort-numeric-desc' => array(
                'class' => 'fa fa-sort-numeric-desc',
                'label' => 'Sort Numeric DESC'
            ),
            'fa fa-thumbs-up' => array(
                'class' => 'fa fa-thumbs-up',
                'label' => 'Thumbs Up'
            ),
            'fa fa-thumbs-down' => array(
                'class' => 'fa fa-thumbs-down',
                'label' => 'Thumbs Down'
            ),
            'fa fa-youtube-square' => array(
                'class' => 'fa fa-youtube-square',
                'label' => 'Youtube Square'
            ),
            'fa fa-youtube' => array(
                'class' => 'fa fa-youtube',
                'label' => 'Youtube'
            ),
            'fa fa-xing' => array(
                'class' => 'fa fa-xing',
                'label' => 'Xing'
            ),
            'fa fa-xing-square' => array(
                'class' => 'fa fa-xing-square',
                'label' => 'Xing Square'
            ),
            'fa fa-youtube-play' => array(
                'class' => 'fa fa-youtube-play',
                'label' => 'Youtube Play'
            ),
            'fa fa-dropbox' => array(
                'class' => 'fa fa-dropbox',
                'label' => 'Dropbox'
            ),
            'fa fa-stack-overflow' => array(
                'class' => 'fa fa-stack-overflow',
                'label' => 'Stack Overflow'
            ),
            'fa fa-instagram' => array(
                'class' => 'fa fa-instagram',
                'label' => 'Instagram'
            ),
            'fa fa-flickr' => array(
                'class' => 'fa fa-flickr',
                'label' => 'Flickr'
            ),
            'fa fa-adn' => array(
                'class' => 'fa fa-adn',
                'label' => 'ADN'
            ),
            'fa fa-bitbucket' => array(
                'class' => 'fa fa-bitbucket',
                'label' => 'Bitbucket'
            ),
            'fa fa-bitbucket-square' => array(
                'class' => 'fa fa-bitbucket-square',
                'label' => 'Bitbucket Square'
            ),
            'fa fa-tumblr' => array(
                'class' => 'fa fa-tumblr',
                'label' => 'Tumblr'
            ),
            'fa fa-tumblr-square' => array(
                'class' => 'fa fa-tumblr-square',
                'label' => 'Tumblr Square'
            ),
            'fa fa-long-arrow-down' => array(
                'class' => 'fa fa-long-arrow-down',
                'label' => 'Long Arrow Down'
            ),
            'fa fa-long-arrow-up' => array(
                'class' => 'fa fa-long-arrow-up',
                'label' => 'Long Arrow Up'
            ),
            'fa fa-long-arrow-left' => array(
                'class' => 'fa fa-long-arrow-left',
                'label' => 'Long Arrow Left'
            ),
            'fa fa-long-arrow-right' => array(
                'class' => 'fa fa-long-arrow-right',
                'label' => 'Long Arrow Right'
            ),
            'fa fa-apple' => array(
                'class' => 'fa fa-apple',
                'label' => 'Apple'
            ),
            'fa fa-windows' => array(
                'class' => 'fa fa-windows',
                'label' => 'Windows'
            ),
            'fa fa-android' => array(
                'class' => 'fa fa-android',
                'label' => 'Android'
            ),
            'fa fa-linux' => array(
                'class' => 'fa fa-linux',
                'label' => 'Linux'
            ),
            'fa fa-dribbble' => array(
                'class' => 'fa fa-dribbble',
                'label' => 'Dribbble'
            ),
            'fa fa-skype' => array(
                'class' => 'fa fa-skype',
                'label' => 'Skype'
            ),
            'fa fa-foursquare' => array(
                'class' => 'fa fa-foursquare',
                'label' => 'Foursquare'
            ),
            'fa fa-trello' => array(
                'class' => 'fa fa-trello',
                'label' => 'Trello'
            ),
            'fa fa-female' => array(
                'class' => 'fa fa-female',
                'label' => 'Female'
            ),
            'fa fa-male' => array(
                'class' => 'fa fa-male',
                'label' => 'Male'
            ),
            'fa fa-gittip' => array(
                'class' => 'fa fa-gittip',
                'label' => 'Gittip, Gratipay'
            ),
            'fa fa-sun-o' => array(
                'class' => 'fa fa-sun-o',
                'label' => 'Sun O'
            ),
            'fa fa-moon-o' => array(
                'class' => 'fa fa-moon-o',
                'label' => 'Moon O'
            ),
            'fa fa-archive' => array(
                'class' => 'fa fa-archive',
                'label' => 'Archive'
            ),
            'fa fa-bug' => array(
                'class' => 'fa fa-bug',
                'label' => 'Bug'
            ),
            'fa fa-vk' => array(
                'class' => 'fa fa-vk',
                'label' => 'VK'
            ),
            'fa fa-weibo' => array(
                'class' => 'fa fa-weibo',
                'label' => 'Weibo'
            ),
            'fa fa-renren' => array(
                'class' => 'fa fa-renren',
                'label' => 'Renren'
            ),
            'fa fa-pagelines' => array(
                'class' => 'fa fa-pagelines',
                'label' => 'Pagelines'
            ),
            'fa fa-stack-exchange' => array(
                'class' => 'fa fa-stack-exchange',
                'label' => 'Stack Exchange'
            ),
            'fa fa-arrow-circle-o-right' => array(
                'class' => 'fa fa-arrow-circle-o-right',
                'label' => 'Arrow Circle O Right'
            ),
            'fa fa-arrow-circle-o-left' => array(
                'class' => 'fa fa-arrow-circle-o-left',
                'label' => 'Arrow Circle O Left'
            ),
            'fa fa-toggle-left' => array(
                'class' => 'fa fa-toggle-left',
                'label' => 'Toggle Left, Caret Square O Left'
            ),
            'fa fa-dot-circle-o' => array(
                'class' => 'fa fa-dot-circle-o',
                'label' => 'Dot Circle O'
            ),
            'fa fa-wheelchair' => array(
                'class' => 'fa fa-wheelchair',
                'label' => 'Wheelchair'
            ),
            'fa fa-vimeo-square' => array(
                'class' => 'fa fa-vimeo-square',
                'label' => 'Vimeo Square'
            ),
            'fa fa-try' => array(
                'class' => 'fa fa-try',
                'label' => 'Turkish Lira, TRY'
            ),
            'fa fa-plus-square-o' => array(
                'class' => 'fa fa-plus-square-o',
                'label' => 'Plus Square O'
            ),
            'fa fa-space-shuttle' => array(
                'class' => 'fa fa-space-shuttle',
                'label' => 'Space Shuttle'
            ),
            'fa fa-slack' => array(
                'class' => 'fa fa-slack',
                'label' => 'Slack'
            ),
            'fa fa-envelope-square' => array(
                'class' => 'fa fa-envelope-square',
                'label' => 'Envelope Square'
            ),
            'fa fa-wordpress' => array(
                'class' => 'fa fa-wordpress',
                'label' => 'Wordpress'
            ),
            'fa fa-openid' => array(
                'class' => 'fa fa-openid',
                'label' => 'Openid'
            ),
            'fa fa-bank' => array(
                'class' => 'fa fa-bank',
                'label' => 'Institution, Bank, Univerity'
            ),
            'fa fa-graduation-cap' => array(
                'class' => 'fa fa-graduation-cap',
                'label' => 'Mortar Board, Graduation Cap'
            ),
            'fa fa-yahoo' => array(
                'class' => 'fa fa-yahoo',
                'label' => 'Yahoo'
            ),
            'fa fa-google' => array(
                'class' => 'fa fa-google',
                'label' => 'Google'
            ),
            'fa fa-reddit' => array(
                'class' => 'fa fa-reddit',
                'label' => 'Reddit'
            ),
            'fa fa-reddit-square' => array(
                'class' => 'fa fa-reddit-square',
                'label' => 'Reddit Square'
            ),
            'fa fa-stumbleupon-circle' => array(
                'class' => 'fa fa-stumbleupon-circle',
                'label' => 'Stumbleupon Circle'
            ),
            'fa fa-stumbleupon' => array(
                'class' => 'fa fa-stumbleupon',
                'label' => 'Stumbleupon'
            ),
            'fa fa-delicious' => array(
                'class' => 'fa fa-delicious',
                'label' => 'Delicious'
            ),
            'fa fa-digg' => array(
                'class' => 'fa fa-digg',
                'label' => 'Digg'
            ),
            'fa fa-pied-piper' => array(
                'class' => 'fa fa-pied-piper',
                'label' => 'Pied Piper'
            ),
            'fa fa-pied-piper-alt' => array(
                'class' => 'fa fa-pied-piper-alt',
                'label' => 'Pied Piper Alt'
            ),
            'fa fa-drupal' => array(
                'class' => 'fa fa-drupal',
                'label' => 'Drupal'
            ),
            'fa fa-joomla' => array(
                'class' => 'fa fa-joomla',
                'label' => 'Joomla'
            ),
            'fa fa-language' => array(
                'class' => 'fa fa-language',
                'label' => 'Language'
            ),
            'fa fa-fax' => array(
                'class' => 'fa fa-fax',
                'label' => 'Fax'
            ),
            'fa fa-building' => array(
                'class' => 'fa fa-building',
                'label' => 'Building'
            ),
            'fa fa-child' => array(
                'class' => 'fa fa-child',
                'label' => 'Child'
            ),
            'fa fa-paw' => array(
                'class' => 'fa fa-paw',
                'label' => 'Paw'
            ),
            'fa fa-spoon' => array(
                'class' => 'fa fa-spoon',
                'label' => 'Spoon'
            ),
            'fa fa-cube' => array(
                'class' => 'fa fa-cube',
                'label' => 'Cube'
            ),
            'fa fa-cubes' => array(
                'class' => 'fa fa-cubes',
                'label' => 'Cubes'
            ),
            'fa fa-behance' => array(
                'class' => 'fa fa-behance',
                'label' => 'Behance'
            ),
            'fa fa-behance-square' => array(
                'class' => 'fa fa-behance-square',
                'label' => 'Behance Square'
            ),
            'fa fa-steam' => array(
                'class' => 'fa fa-steam',
                'label' => 'Steam'
            ),
            'fa fa-steam-square' => array(
                'class' => 'fa fa-steam-square',
                'label' => 'Steam Square'
            ),
            'fa fa-recycle' => array(
                'class' => 'fa fa-recycle',
                'label' => 'Recycle'
            ),
            'fa fa-car' => array(
                'class' => 'fa fa-car',
                'label' => 'Car, Automobile'
            ),
            'fa fa-taxi' => array(
                'class' => 'fa fa-taxi',
                'label' => 'Taxi, Cab'
            ),
            'fa fa-tree' => array(
                'class' => 'fa fa-tree',
                'label' => 'Tree'
            ),
            'fa fa-spotify' => array(
                'class' => 'fa fa-spotify',
                'label' => 'Spotify'
            ),
            'fa fa-deviantart' => array(
                'class' => 'fa fa-deviantart',
                'label' => 'Deviantart'
            ),
            'fa fa-soundcloud' => array(
                'class' => 'fa fa-soundcloud',
                'label' => 'Soundcloud'
            ),
            'fa fa-database' => array(
                'class' => 'fa fa-database',
                'label' => 'Database'
            ),
            'fa fa-file-pdf-o' => array(
                'class' => 'fa fa-file-pdf-o',
                'label' => 'File PDF O'
            ),
            'fa fa-file-word-o' => array(
                'class' => 'fa fa-file-word-o',
                'label' => 'File Word O'
            ),
            'fa fa-file-excel-o' => array(
                'class' => 'fa fa-file-excel-o',
                'label' => 'Fiel Excel O'
            ),
            'fa fa-file-powerpoint-o' => array(
                'class' => 'fa fa-file-powerpoint-o',
                'label' => 'File Powerpoint O'
            ),
            'fa fa-file-image-o' => array(
                'class' => 'fa fa-file-image-o',
                'label' => 'Photo O, Picture O, Image O'
            ),
            'fa fa-file-zip-o' => array(
                'class' => 'fa fa-file-zip-o',
                'label' => 'Archive O, Zip O'
            ),
            'fa fa-file-audio-o' => array(
                'class' => 'fa fa-file-audio-o',
                'label' => 'File Audio O, File Sound O'
            ),
            'fa fa-file-movie-o' => array(
                'class' => 'fa fa-file-movie-o',
                'label' => 'File Movie O, File Video O'
            ),
            'fa fa-file-code-o' => array(
                'class' => 'fa fa-file-code-o',
                'label' => 'File Code O'
            ),
            'fa fa-vine' => array(
                'class' => 'fa fa-vine',
                'label' => 'Vine'
            ),
            'fa fa-codepen' => array(
                'class' => 'fa fa-codepen',
                'label' => 'Codepen'
            ),
            'fa fa-jsfiddle' => array(
                'class' => 'fa fa-jsfiddle',
                'label' => 'JSFiddle'
            ),
            'fa fa-support' => array(
                'class' => 'fa fa-support',
                'label' => 'Life Bouy, Life Buoy, Life Saver, Support, Life Ring'
            ),
            'fa fa-circle-o-notch' => array(
                'class' => 'fa fa-circle-o-notch',
                'label' => 'Circle O Notch'
            ),
            'fa fa-rebel' => array(
                'class' => 'fa fa-rebel',
                'label' => 'RA, Rebel'
            ),
            'fa fa-empire' => array(
                'class' => 'fa fa-empire',
                'label' => 'GE, Empire'
            ),
            'fa fa-git-square' => array(
                'class' => 'fa fa-git-square',
                'label' => 'Git Square'
            ),
            'fa fa-git' => array(
                'class' => 'fa fa-git',
                'label' => 'Git'
            ),
            'fa fa-hacker-news' => array(
                'class' => 'fa fa-hacker-news',
                'label' => 'Hacker News'
            ),
            'fa fa-tencent-weibo' => array(
                'class' => 'fa fa-tencent-weibo',
                'label' => 'Tencent Weibo'
            ),
            'fa fa-qq' => array(
                'class' => 'fa fa-qq',
                'label' => 'QQ'
            ),
            'fa fa-wechat' => array(
                'class' => 'fa fa-wechat',
                'label' => 'Wechat, Weixin'
            ),
            'fa fa-send' => array(
                'class' => 'fa fa-send',
                'label' => 'Send, Paper Plane'
            ),
            'fa fa-send-o' => array(
                'class' => 'fa fa-send-o',
                'label' => 'Send O, Paper Plane O'
            ),
            'fa fa-history' => array(
                'class' => 'fa fa-history',
                'label' => 'History'
            ),
            'fa fa-genderless' => array(
                'class' => 'fa fa-genderless',
                'label' => 'Genderless, Circle Thin'
            ),
            'fa fa-header' => array(
                'class' => 'fa fa-header',
                'label' => 'Header'
            ),
            'fa fa-paragraph' => array(
                'class' => 'fa fa-paragraph',
                'label' => 'Paragraph'
            ),
            'fa fa-sliders' => array(
                'class' => 'fa fa-sliders',
                'label' => 'Sliders'
            ),
            'fa fa-share-alt' => array(
                'class' => 'fa fa-share-alt',
                'label' => 'Share Alt'
            ),
            'fa fa-share-alt-square' => array(
                'class' => 'fa fa-share-alt-square',
                'label' => 'Share Alt Square'
            ),
            'fa fa-bomb' => array(
                'class' => 'fa fa-bomb',
                'label' => 'Bomb'
            ),
            'fa fa-futbol-o' => array(
                'class' => 'fa fa-futbol-o',
                'label' => 'Soccer Ball O, Football O'
            ),
            'fa fa-tty' => array(
                'class' => 'fa fa-tty',
                'label' => 'TTY'
            ),
            'fa fa-binoculars' => array(
                'class' => 'fa fa-binoculars',
                'label' => 'Binoculars'
            ),
            'fa fa-plug' => array(
                'class' => 'fa fa-plug',
                'label' => 'Plug'
            ),
            'fa fa-slideshare' => array(
                'class' => 'fa fa-slideshare',
                'label' => 'Slideshare'
            ),
            'fa fa-twitch' => array(
                'class' => 'fa fa-twitch',
                'label' => 'Twitch'
            ),
            'fa fa-yelp' => array(
                'class' => 'fa fa-yelp',
                'label' => 'Yelp'
            ),
            'fa fa-newspaper-o' => array(
                'class' => 'fa fa-newspaper-o',
                'label' => 'Newspaper O'
            ),
            'fa fa-wifi' => array(
                'class' => 'fa fa-wifi',
                'label' => 'WiFi'
            ),
            'fa fa-calculator' => array(
                'class' => 'fa fa-calculator',
                'label' => 'Calculator'
            ),
            'fa fa-paypal' => array(
                'class' => 'fa fa-paypal',
                'label' => 'PayPal'
            ),
            'fa fa-google-wallet' => array(
                'class' => 'fa fa-google-wallet',
                'label' => 'Google Wallet'
            ),
            'fa fa-cc-visa' => array(
                'class' => 'fa fa-cc-visa',
                'label' => 'CC Visa'
            ),
            'fa fa-cc-mastercard' => array(
                'class' => 'fa fa-cc-mastercard',
                'label' => 'CC MasterCard'
            ),
            'fa fa-cc-discover' => array(
                'class' => 'fa fa-cc-discover',
                'label' => 'CC Discover'
            ),
            'fa fa-cc-amex' => array(
                'class' => 'fa fa-cc-amex',
                'label' => 'CC Amex'
            ),
            'fa fa-cc-paypal' => array(
                'class' => 'fa fa-cc-paypal',
                'label' => 'CC PayPal'
            ),
            'fa fa-cc-stripe' => array(
                'class' => 'fa fa-cc-stripe',
                'label' => 'CC Stripe'
            ),
            'fa fa-bell-slash' => array(
                'class' => 'fa fa-bell-slash',
                'label' => 'Bell Slash'
            ),
            'fa fa-bell-slash-o' => array(
                'class' => 'fa fa-bell-slash-o',
                'label' => 'Bell Slash O'
            ),
            'fa fa-trash' => array(
                'class' => 'fa fa-trash',
                'label' => 'Trash'
            ),
            'fa fa-copyright' => array(
                'class' => 'fa fa-copyright',
                'label' => 'Copyright'
            ),
            'fa fa-at' => array(
                'class' => 'fa fa-at',
                'label' => 'At'
            ),
            'fa fa-eyedropper' => array(
                'class' => 'fa fa-eyedropper',
                'label' => 'Eyedropper'
            ),
            'fa fa-paint-brush' => array(
                'class' => 'fa fa-paint-brush',
                'label' => 'Paint Brush'
            ),
            'fa fa-birthday-cake' => array(
                'class' => 'fa fa-birthday-cake',
                'label' => 'Birthday Cake'
            ),
            'fa fa-area-chart' => array(
                'class' => 'fa fa-area-chart',
                'label' => 'Area Chart'
            ),
            'fa fa-pie-chart' => array(
                'class' => 'fa fa-pie-chart',
                'label' => 'Pie Chart'
            ),
            'fa fa-line-chart' => array(
                'class' => 'fa fa-line-chart',
                'label' => 'Line Chart'
            ),
            'fa fa-lastfm' => array(
                'class' => 'fa fa-lastfm',
                'label' => 'LastFM'
            ),
            'fa fa-lastfm-square' => array(
                'class' => 'fa fa-lastfm-square',
                'label' => 'LastFM Square'
            ),
            'fa fa-toggle-off' => array(
                'class' => 'fa fa-toggle-off',
                'label' => 'Toggle Off'
            ),
            'fa fa-toggle-on' => array(
                'class' => 'fa fa-toggle-on',
                'label' => 'Toggle On'
            ),
            'fa fa-bicycle' => array(
                'class' => 'fa fa-bicycle',
                'label' => 'Bicycle'
            ),
            'fa fa-bus' => array(
                'class' => 'fa fa-bus',
                'label' => 'Bus'
            ),
            'fa fa-ioxhost' => array(
                'class' => 'fa fa-ioxhost',
                'label' => 'IoxHost'
            ),
            'fa fa-angellist' => array(
                'class' => 'fa fa-angellist',
                'label' => 'AngelList'
            ),
            'fa fa-cc' => array(
                'class' => 'fa fa-cc',
                'label' => 'CC'
            ),
            'fa fa-shekel' => array(
                'class' => 'fa fa-shekel',
                'label' => 'Sheckel, Sheqel, ILS'
            ),
            'fa fa-meanpath' => array(
                'class' => 'fa fa-meanpath',
                'label' => 'Meanpath'
            ),
            'fa fa-buysellads' => array(
                'class' => 'fa fa-buysellads',
                'label' => 'BuySellAds'
            ),
            'fa fa-connectdevelop' => array(
                'class' => 'fa fa-connectdevelop',
                'label' => 'ConnectDevelop'
            ),
            'fa fa-dashcube' => array(
                'class' => 'fa fa-dashcube',
                'label' => 'DashCube'
            ),
            'fa fa-forumbee' => array(
                'class' => 'fa fa-forumbee',
                'label' => 'ForumBee'
            ),
            'fa fa-leanpub' => array(
                'class' => 'fa fa-leanpub',
                'label' => 'LeanPub'
            ),
            'fa fa-sellsy' => array(
                'class' => 'fa fa-sellsy',
                'label' => 'SellSpy'
            ),
            'fa fa-shirtsinbulk' => array(
                'class' => 'fa fa-shirtsinbulk',
                'label' => 'ShirtsInBulk'
            ),
            'fa fa-simplybuilt' => array(
                'class' => 'fa fa-simplybuilt',
                'label' => 'SimplyBuilt'
            ),
            'fa fa-skyatlas' => array(
                'class' => 'fa fa-skyatlas',
                'label' => 'SkyAtlas'
            ),
            'fa fa-cart-plus' => array(
                'class' => 'fa fa-cart-plus',
                'label' => 'CartPlus'
            ),
            'fa fa-cart-arrow-down' => array(
                'class' => 'fa fa-cart-arrow-down',
                'label' => 'Cart Arrow Down'
            ),
            'fa fa-diamond' => array(
                'class' => 'fa fa-diamond',
                'label' => 'Diamond'
            ),
            'fa fa-ship' => array(
                'class' => 'fa fa-ship',
                'label' => 'Ship'
            ),
            'fa fa-user-secret' => array(
                'class' => 'fa fa-user-secret',
                'label' => 'User Secret'
            ),
            'fa fa-motorcycle' => array(
                'class' => 'fa fa-motorcycle',
                'label' => 'Motorcycle'
            ),
            'fa fa-street-view' => array(
                'class' => 'fa fa-street-view',
                'label' => 'Street View'
            ),
            'fa fa-heartbeat' => array(
                'class' => 'fa fa-heartbeat',
                'label' => 'Heartbeat'
            ),
            'fa fa-venus' => array(
                'class' => 'fa fa-venus',
                'label' => 'Venus'
            ),
            'fa fa-mars' => array(
                'class' => 'fa fa-mars',
                'label' => 'Mars'
            ),
            'fa fa-mercury' => array(
                'class' => 'fa fa-mercury',
                'label' => 'Mercury'
            ),
            'fa fa-transgender' => array(
                'class' => 'fa fa-transgender',
                'label' => 'Transgender'
            ),
            'fa fa-transgender-alt' => array(
                'class' => 'fa fa-transgender-alt',
                'label' => 'Transgender Alt'
            ),
            'fa fa-venus-double' => array(
                'class' => 'fa fa-venus-double',
                'label' => 'Venus Double'
            ),
            'fa fa-mars-double' => array(
                'class' => 'fa fa-mars-double',
                'label' => 'Mars Double'
            ),
            'fa fa-venus-mars' => array(
                'class' => 'fa fa-venus-mars',
                'label' => 'Venus Mars'
            ),
            'fa fa-mars-stroke' => array(
                'class' => 'fa fa-mars-stroke',
                'label' => 'Mars Stroke'
            ),
            'fa fa-mars-stroke-v' => array(
                'class' => 'fa fa-mars-stroke-v',
                'label' => 'Mars Stroke Vertical'
            ),
            'fa fa-mars-stroke-h' => array(
                'class' => 'fa fa-mars-stroke-h',
                'label' => 'Mars Stroke Horizontal'
            ),
            'fa fa-neuter' => array(
                'class' => 'fa fa-neuter',
                'label' => 'Neuter'
            ),
            'fa fa-facebook-official' => array(
                'class' => 'fa fa-facebook-official',
                'label' => 'Facebook Official'
            ),
            'fa fa-pinterest-p' => array(
                'class' => 'fa fa-pinterest-p',
                'label' => 'Pinterest P'
            ),
            'fa fa-whatsapp' => array(
                'class' => 'fa fa-whatsapp',
                'label' => 'Whatsapp'
            ),
            'fa fa-server' => array(
                'class' => 'fa fa-server',
                'label' => 'Server'
            ),
            'fa fa-user-plus' => array(
                'class' => 'fa fa-user-plus',
                'label' => 'User Plus'
            ),
            'fa fa-user-times' => array(
                'class' => 'fa fa-user-times',
                'label' => 'User Times'
            ),
            'fa fa-bed' => array(
                'class' => 'fa fa-bed',
                'label' => 'Bed, Hotel'
            ),
            'fa fa-viacoin' => array(
                'class' => 'fa fa-viacoin',
                'label' => 'Viacoin'
            ),
            'fa fa-train' => array(
                'class' => 'fa fa-train',
                'label' => 'Train'
            ),
            'fa fa-subway' => array(
                'class' => 'fa fa-subway',
                'label' => 'Subway'
            ),
            'fa fa-medium' => array(
                'class' => 'fa fa-medium',
                'label' => 'Medium'
            ),
        );

        if ($useEmptyIcon) {
	        $empty = array(
		        'none' => array(
			        'class' => 'fa',
			        'label' => 'None'
		        )
	        );
	        $IconList = array_merge($empty, $IconList);
        }

        return $IconList;
    }	
}
