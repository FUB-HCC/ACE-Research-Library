<?php
function enqueue_thirdparty_scripts() {
    wp_enqueue_style(
        'bootstrap-css',
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style(
        'bootstrap-theme-css',
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css',
        array('bootstrap-css')
    );
    wp_enqueue_script(
        'bootstrap-js',
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
        array('jquery'));
    wp_enqueue_script(
        'angular-js',
        'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js'
    );
    wp_enqueue_script(
        'angular-animate-js',
        'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-animate.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'angular-aria-js',
        'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-aria.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'angular-messages-js',
        'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-messages.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'angular-route-js',
        'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-route.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'angular-material-js',
        'https://gitcdn.link/repo/angular/bower-material/master/angular-material.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'ui-bootstrap-js',
        'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.0.0/ui-bootstrap.min.js',
        array('bootstrap-js', 'angular-js')
    );
    wp_enqueue_script(
        'ui-bootstrap-tpls-js',
        'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.0.0/ui-bootstrap-tpls.min.js',
        array('ui-bootstrap-js')
    );
    wp_enqueue_script(
        'angularjs-dropdown-multiselect',
        'https://rawgit.com/dotansimha/angularjs-dropdown-multiselect/master/src/angularjs-dropdown-multiselect.js',
        array('ui-bootstrap-tpls-js')
    );
}
add_action('wp_enqueue_scripts', 'enqueue_thirdparty_scripts', 5);
function enqueue_acerl_scripts() {
    wp_enqueue_script(
        'acerl-config-app-js',
        get_stylesheet_directory_uri() . '/js/config_app.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'acerl-route-js',
        get_stylesheet_directory_uri() . '/js/route.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'acerl-controller-js',
        get_stylesheet_directory_uri() . '/js/controller/controller.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'acerl-controller-search-js',
        get_stylesheet_directory_uri() . '/js/controller/controller_search.js',
        array('angular-js')
    );
    wp_enqueue_script(
        'acerl-services-db-js',
        get_stylesheet_directory_uri() . '/js/services/db.js',
        array('angular-js')
    );
}
add_action('wp_enqueue_scripts', 'enqueue_acerl_scripts');
?>
