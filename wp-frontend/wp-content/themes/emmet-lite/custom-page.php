<?php

$mp_emmet_sections = array();

if (!(get_theme_mod('theme_bigtitle_show', false) || get_theme_mod('theme_bigtitle_show'))):
    $mp_emmet_sections['big_title.php'] = esc_html(get_theme_mod('theme_bigtitle_position', 10));
endif;
if (!(get_theme_mod('theme_welcome_show', false) || get_theme_mod('theme_welcome_show'))):
    $mp_emmet_sections['welcome.php'] = esc_html(get_theme_mod('theme_welcome_position', 20));
endif;
if (!(get_theme_mod('theme_third_show', false) || get_theme_mod('theme_third_show'))):
    $mp_emmet_sections["third.php"] = esc_html(get_theme_mod('theme_third_position', 30));
endif;
if (!(get_theme_mod('theme_install_show', false) || get_theme_mod('theme_install_show'))):
    $mp_emmet_sections["install.php"] = esc_html(get_theme_mod('theme_install_position', 40));
endif;
if (!(get_theme_mod('theme_features_show', false) || get_theme_mod('theme_features_show'))):
    $mp_emmet_sections["features.php"] = esc_html(get_theme_mod('theme_features_position', 50));
endif;
if (!(get_theme_mod('theme_portfolio_show', false) || get_theme_mod('theme_portfolio_show'))):
    $mp_emmet_sections["portfolio.php"] = esc_html(get_theme_mod('theme_portfolio_position', 60));
endif;
if (!(get_theme_mod('theme_plan_show', false) || get_theme_mod('theme_plan_show'))):
    $mp_emmet_sections["plan.php"] = esc_html(get_theme_mod('theme_plan_position', 70));
endif;
if (!(get_theme_mod('theme_accent_show', false) || get_theme_mod('theme_accent_show'))):
    $mp_emmet_sections["accent.php"] = esc_html(get_theme_mod('theme_accent_position', 80));
endif;
if (!(get_theme_mod('theme_team_show', false) || get_theme_mod('theme_team_show'))):
    $mp_emmet_sections["team.php"] = esc_html(get_theme_mod('theme_team_position', 90));
endif;
if (!(get_theme_mod('theme_subscribe_show', false) || get_theme_mod('theme_subscribe_show'))):
    $mp_emmet_sections["subscribe.php"] = esc_html(get_theme_mod('theme_subscribe_position', 100));
endif;
if (!(get_theme_mod('theme_lastnews_show', false) || get_theme_mod('theme_lastnews_show'))):
    $mp_emmet_sections["lastnews.php"] = esc_html(get_theme_mod('theme_lastnews_position', 110));
endif;
if (!(get_theme_mod('theme_testimonials_show', false) || get_theme_mod('theme_testimonials_show'))):
    $mp_emmet_sections["testimonials.php"] = esc_html(get_theme_mod('theme_testimonials_position', 120));
endif;
if (!(get_theme_mod('theme_googlemap_show', false) || get_theme_mod('theme_googlemap_show'))):
    $mp_emmet_sections["map.php"] = esc_html(get_theme_mod('theme_googlemap_position', 130));
endif;
if (!(get_theme_mod('theme_contact_show', false) || get_theme_mod('theme_contact_show'))):
    $mp_emmet_sections["contact.php"] = esc_html(get_theme_mod('theme_contact_position', 140));
endif;



asort($mp_emmet_sections);
foreach ($mp_emmet_sections as $key => $val) {
    include get_template_directory() . "/sections/" . $key;
}