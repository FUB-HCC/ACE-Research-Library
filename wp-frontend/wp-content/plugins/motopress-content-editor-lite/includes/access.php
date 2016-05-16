<?php
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	global $motopressCESettings;
    require_once $motopressCESettings['plugin_dir_path'] . 'includes/ce/Access.php';
    $ceAccess = new MPCEAccess();
    $access = $ceAccess->hasAccess($_POST['postID']);

    if (!$access) {
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/functions.php';
        require_once $motopressCESettings['plugin_dir_path'] . 'includes/getLanguageDict.php';
        global $motopressCELang;
        $motopressCELang = motopressCEGetLanguageDict();
        motopressCESetError($motopressCELang->permissionDenied);
    }
}