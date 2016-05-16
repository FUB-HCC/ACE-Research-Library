<?php
if (!defined('ABSPATH')) exit;

// Global Actions/Filters
add_filter('wpseo_whitelist_permalink_vars', 'motopressCEWpseoWhitelistPermalinkVars');
add_filter('cherry_standard_post_content_list', 'motopressCECherryStandardPostContentListFix');
add_filter('cherry_standard_post_content_search', 'motopressCECherryStandardPostContentSearchFix');
add_action('motopress_render_shortcode', 'motopressCEWPInsertFix');
add_action('motopress_render_shortcode', 'motopressCEPressTicketFix');
// End Global Action/Filters

// Editor Action/Filters
if (isset($_GET['motopress-ce']) && $_GET['motopress-ce'] == 1) {
    add_action('setup_theme', 'motopressCEPolylangFix', 9);
    add_action('init', 'motopressCEWPGoogleMapsFix');
    add_action('init', 'motopressCECopyrightProofFix');
    remove_action('wp_footer', 'FT_Process'); // Deactivate FlexyTalk    
}
// End Editor Action/Filters

/*************** Functions ***************/

/*
 * WordPress SEO. Add "motopress-ce" to whitelist permalink vars.
 */
function motopressCEWpseoWhitelistPermalinkVars($vars) {
    $vars[] = 'motopress-ce';
    $vars[] = 'mpce-post-id';
    return $vars;
}

/*
 * Polylang plugin. Prevent home redirect.
 */
function motopressCEPolylangFix() {
    add_filter('pll_redirect_home', '__return_false');
}

/*
 * Fixes an issue with "WP Google Maps" plugin by wpgmaps.com.
 */
function motopressCEWPGoogleMapsFix() {
    remove_action('wp_enqueue_scripts', 'wpgmaps_load_jquery', 9999);
}

/*
 * Disable Copyright Proof plugin (frustrate_copy.js).
 */
function motopressCECopyrightProofFix() {
    remove_action("wp_head", "dprv_head");
}

/*
 * Fix Cherry Post Output
 */
function motopressCECherryStandardPostContentListFix($content) {
    return wp_trim_words(apply_filters('the_content', get_the_content()), 55);
}
function motopressCECherryStandardPostContentSearchFix($content) {
    return wp_trim_words(apply_filters('the_content', get_the_content()), 55);
}

/*
 * Fixes an issue with "WP-Insert" plugin by Namith Jawahar. "WP-Insert" plugin adds custom code to each the_content filter call.
 */
function motopressCEWPInsertFix() {
    remove_filter('the_content', 'wp_insert_legal_filter_the_content');
    remove_filter('the_content', 'wp_insert_inpostads_filter_the_content', 100);
    remove_action('the_content', 'wp_insert_track_post_instance', 1);
}


/*
 * Fixes an issue with PressTicket plugin by Toan Nguyen http://wpoffice.net/contact/. PressTicket plugin adds custom div to each the_content filter call.
 */
function motopressCEPressTicketFix() {
    remove_action('the_content', 'wpo_ticket_after_single');
}