<?php

/*
 * hook sections of front page
 */

/*
 * Features section
 *
 * @see mp_emmet_after_sidebar_features_function()
 * @see mp_emmet_before_sidebar_features_function()
 */
add_action('mp_emmet_after_sidebar_features', 'mp_emmet_after_sidebar_features_function', 10);
add_action('mp_emmet_before_sidebar_features', 'mp_emmet_before_sidebar_features_function', 10);
/*
 * Google map section
 *
 * @see mp_emmet_after_sidebar_googlemap_function()
 * @see mp_emmet_before_sidebar_googlemap_function()
 */
add_action('mp_emmet_after_sidebar_googlemap', 'mp_emmet_after_sidebar_googlemap_function', 10);
add_action('mp_emmet_before_sidebar_googlemap', 'mp_emmet_before_sidebar_googlemap_function', 10);
/*
 * Plan section
 *
 * @see mp_emmet_after_sidebar_plan_function()
 * @see mp_emmet_before_sidebar_plan_function()
 */
add_action('mp_emmet_after_sidebar_plan', 'mp_emmet_after_sidebar_plan_function', 10);
add_action('mp_emmet_before_sidebar_plan', 'mp_emmet_before_sidebar_plan_function', 10);
/*
 * Subscribe section
 *
 * @see mp_emmet_after_sidebar_subscribe_function()
 * @see mp_emmet_before_sidebar_subscribe_function()
 */
add_action('mp_emmet_after_sidebar_subscribe', 'mp_emmet_after_sidebar_subscribe_function', 10);
add_action('mp_emmet_before_sidebar_subscribe', 'mp_emmet_before_sidebar_subscribe_function', 10);
/*
 * Team section
 *
 * @see mp_emmet_after_sidebar_team_function()
 * @see mp_emmet_before_sidebar_team_function()
 */
add_action('mp_emmet_after_sidebar_team', 'mp_emmet_after_sidebar_team_function', 10);
add_action('mp_emmet_before_sidebar_team', 'mp_emmet_before_sidebar_team_function', 10);
/*
 * Testimonials section
 *
 * @see mp_emmet_after_sidebar_testimonials_function()
 * @see mp_emmet_before_sidebar_testimonials_function()
 */
add_action('mp_emmet_after_sidebar_testimonials', 'mp_emmet_after_sidebar_testimonials_function', 10);
add_action('mp_emmet_before_sidebar_testimonials', 'mp_emmet_before_sidebar_testimonials_function', 10);
