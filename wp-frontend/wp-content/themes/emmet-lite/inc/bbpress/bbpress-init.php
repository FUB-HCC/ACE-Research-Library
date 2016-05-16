<?php

/*
 *  Change bbPress bread .
 */

function mp_emmet_bbp_breadcrumb() {
    $args = array(
        'before' => '<div class="bbp-breadcrumb breadcrumb breadcrumbs sp-breadcrumbs"><div class="breadcrumb-trail">',
        'after' => '</div></div>',
        'sep' => '<span class="sep"><i class="fa fa-angle-right"></i></span>',
        'home_text' => __('Home', 'emmet-lite')
    );
    return $args;
}

add_filter('bbp_before_get_breadcrumb_parse_args', 'mp_emmet_bbp_breadcrumb');

