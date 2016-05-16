<?php
function motopressCERenderTemplate() {
    require_once dirname(__FILE__).'/../verifyNonce.php';
    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../access.php';
    require_once dirname(__FILE__).'/../functions.php';
    require_once dirname(__FILE__).'/../getLanguageDict.php';

    global $motopressCELang;
    $errorMessage = strtr($motopressCELang->CERenderError, array('%name%' => $motopressCELang->CETemplate));

    if (isset($_POST['templateId']) && !empty($_POST['templateId'])) {
        global $motopressCESettings;
        $errors = array();

        $templateId = $_POST['templateId'];        
        $motopressCELibrary = MPCELibrary::getInstance();
        $template = &$motopressCELibrary->getTemplate($templateId);
        if ($template) {
	        require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/renderContent.php';

            $content = $template->getContent();
            $content = stripslashes($content);
            $content = motopressCECleanupShortcode($content);
            $content = preg_replace('/\][\s]*/', ']', $content);
            $content = motopressCEWrapOuterCode($content);
            $content = motopressCEParseObjectsRecursive($content);
			$contentWrapper = '<div class="motopress-ce-rendered-content-wrapper">' . $content . '</div>';

            $result = apply_filters('the_content', $contentWrapper);
			$result .= '<div class="motopress-ce-private-styles-updates-wrapper">' . MPCECustomStyleManager::getInstance()->getPrivateStylesTag(true) . '</div>';
			echo $result;

        } else {
            $errors[] = $errorMessage;
        }

        if (!empty($errors)) {
            if ($motopressCESettings['debug']) {
                print_r($errors);
            } else {
                motopressCESetError($errorMessage);
            }
        }
    } else {
        motopressCESetError($errorMessage);
    }
    exit;
}