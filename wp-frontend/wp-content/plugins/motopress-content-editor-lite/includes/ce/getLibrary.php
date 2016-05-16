<?php

function motopressCEGetLibraryAjaxCallback() {
    require_once dirname(__FILE__).'/../verifyNonce.php';
    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../access.php';
    require_once dirname(__FILE__).'/../functions.php';
    require_once dirname(__FILE__).'/Library.php';

    $motopressCELibrary = getMotopressCELibrary();    
    wp_send_json($motopressCELibrary->getData());
    exit;
}