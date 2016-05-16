/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 * Things like site title and description changes.
 */


(function ($) {
    $(document).ready(function () {
        $('body').on('click', '.accordion-section-title', function () {
            if ($('#customize-preview').hasClass('iframe-ready')) {
                var parentid = $(this).parent().attr('id');
                var iframe = $('#customize-preview iframe');
                var iframeContents = iframe.contents();
                if (!iframeContents.find('body').hasClass('page-template-template-front-page')) {
                    return;
                }
                if (parentid === 'accordion-section-theme_bigtitle_section') {
                    mp_theme_preview_scroll(iframeContents, "#big-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_welcome_section') {
                    mp_theme_preview_scroll(iframeContents, "#welcome");
                    return;
                }
                if (parentid === 'accordion-section-theme_third_section') {
                    mp_theme_preview_scroll(iframeContents, "#third");
                    return;
                }
                if (parentid === 'accordion-section-theme_install_section') {
                    mp_theme_preview_scroll(iframeContents, "#install");
                    return;
                }
                if (parentid === 'accordion-section-theme_features_section') {
                    mp_theme_preview_scroll(iframeContents, "#features");
                    return;
                }
                if (parentid === 'accordion-section-theme_portfolio_section') {
                    mp_theme_preview_scroll(iframeContents, "#portfolio");
                    return;
                }
                if (parentid === 'accordion-section-theme_plan_section') {
                    mp_theme_preview_scroll(iframeContents, "#plan");
                    return;
                }
                if (parentid === 'accordion-section-theme_accent_section') {
                    mp_theme_preview_scroll(iframeContents, "#accent");
                    return;
                }
                if (parentid === 'accordion-section-theme_team_section') {
                    mp_theme_preview_scroll(iframeContents, "#team");
                    return;
                }
                if (parentid === 'accordion-section-theme_subscribe_section') {
                    mp_theme_preview_scroll(iframeContents, "#subscribe");
                    return;
                }
                if (parentid === 'accordion-section-theme_lastnews_section') {
                    mp_theme_preview_scroll(iframeContents, "#lastnews");
                    return;
                }
                if (parentid === 'accordion-section-theme_testimonials_section') {
                    mp_theme_preview_scroll(iframeContents, "#testimonials");
                    return;
                }
                if (parentid === 'accordion-section-theme_googlemap_section') {
                    mp_theme_preview_scroll(iframeContents, "#googlemap");
                    return;
                }
                if (parentid === 'accordion-section-theme_contactus_section') {
                    mp_theme_preview_scroll(iframeContents, "#contact");
                    return;
                }
            }
        });
		function mp_theme_preview_scroll(holder, animateto) {
			if ( holder && holder.find(animateto).length ) {
				holder.find('html, body').animate({
					scrollTop: holder.find(animateto).offset().top
				}, 1000);
			}
		}
    });
})(jQuery);
