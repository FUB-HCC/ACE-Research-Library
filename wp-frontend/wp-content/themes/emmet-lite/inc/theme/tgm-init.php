<?php

/**
 * This file represents the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage tgn-init
 * @version    2.5.2
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */
/**
 * Include the MP_Emmet_TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/classes/theme/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'mp_emmet_register_required_plugins');

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * MP_Emmet_TGM_Plugin_Activation class constructor.
 */
function mp_emmet_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array();

    // This is an example of the use of 'is_callable' functionality. A user could - for instance -
    // have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
    // 'wordpress-seo-premium'.
    // By setting 'is_callable' to either a function from that plugin or a class method
    // `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
    // recognize the plugin as being installed.
    if (!(is_plugin_active('motopress-content-editor/motopress-content-editor.php'))) {
      array_push($plugins, array(
      'name' => 'MotoPress Content Editor Lite',
      'slug' => 'motopress-content-editor-lite',
      'is_callable' => 'motopress-content-editor',
      )
      );
      }

    array_push($plugins, array(
        'name' => 'Emmet Theme Engine',
        'slug' => 'mp-emmet',
        'is_callable' => 'mp-emmet',
            )
    );
    array_push($plugins, array(
        'name' => 'Another MailChimp Widget',
        'slug' => 'another-mailchimp-widget',
        'is_callable' => 'another-mailchimp-widget',
            )
    );
    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id' => 'tgmpa', // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '', // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'themes.php', // Parent menu slug.
        'capability' => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true, // Show admin notices or not.
        'dismissable' => true, // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true, // Automatically activate plugins after installation or not.
        'message' => '', // Message to output right before the plugins table.
    );

    tgmpa($plugins, $config);
}

add_action('admin_head', 'mp_emmet_admin_style');

function mp_emmet_admin_style() {
    echo '<style>
    #setting-error-tgmpa{
        display:block;
    } 
  </style>';
}
