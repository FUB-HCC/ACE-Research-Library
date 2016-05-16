<?php
/*
 * woocommerce breadcrumbs
 */
add_filter('woocommerce_breadcrumb_defaults', 'mp_emmet_woocommerce_breadcrumbs');

function mp_emmet_woocommerce_breadcrumbs() {
    return array(
        'delimiter' => ' <span class="sep"><i class="fa fa-angle-right"></i></span> ',
        'wrap_before' => '<div class="container"><div class="breadcrumb breadcrumbs sp-breadcrumbs " itemprop="breadcrumb"><div class="breadcrumb-trail">',
        'wrap_after' => '</div></div></div>',
        'before' => '',
        'after' => '',
        'home' => _x('Home', 'breadcrumb', 'emmet-lite'),
    );
}


/*
 *  Remove the product rating display on product loops
 */
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

/*
 *  Remove them all in one line
 */
add_filter('woocommerce_enqueue_styles', '__return_false');

// define the woocommerce_before_main_content callback
function mp_emmet_woocommerce_before_main_content() {
    echo '';
}

// add the action
add_action('woocommerce_before_main_content', 'mp_emmet_woocommerce_before_main_content', 10, 2);

// define the woocommerce_archive_description callback
function mp_emmet_woocommerce_archive_description() {
    echo '<div class="container main-container"><div class="row clearfix"><div class=" col-xs-12 col-sm-8 col-md-8 col-lg-8">';
}

// add the action
add_action('woocommerce_archive_description', 'mp_emmet_woocommerce_archive_description', 10, 2);

// define the woocommerce_archive_description callback
function mp_emmet_woocommerce_before_single_product() {
    echo '</div><!--container--></div><!-- page-header--><div class="container main-container"><div class="row clearfix"><div class=" col-xs-12 col-sm-8 col-md-8 col-lg-8">';
}

// add the action
add_action('woocommerce_before_single_product', 'mp_emmet_woocommerce_before_single_product', 10, 2);

// define the woocommerce_archive_description callback
function mp_emmet_woocommerce_sidebar() {
    echo '</div><!--col-xs-12 col-sm-4 col-md-4 col-lg-4--> '
    . '</div>'
    . '</div>';
}

// add the action
add_action('woocommerce_sidebar', 'mp_emmet_woocommerce_sidebar', 10, 2);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

function mp_emmet_woocommerce_after_main_content() {
    echo '</div><!--col-xs-12 col-sm-8 col-md-8 col-lg-8--> '
    . '<div class=" col-xs-12 col-sm-4 col-md-4 col-lg-4">';
}

add_action('woocommerce_after_main_content', 'mp_emmet_woocommerce_after_main_content', 10);


