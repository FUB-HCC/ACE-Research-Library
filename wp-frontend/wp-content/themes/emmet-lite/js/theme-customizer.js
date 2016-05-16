/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 * Things like site title and description changes.
 */

(function ($) {
    function menu_align() {
        var headerWrap = $('.site-header');
        var navWrap = $('.navbar');
        var logoWrap = $('.site-logo');
        var containerWrap = $('.container');
        var classToAdd = 'header-align-center';
        if (headerWrap.hasClass(classToAdd))
        {
            headerWrap.removeClass(classToAdd);
        }
        var logoWidth = logoWrap.outerWidth();
        var menuWidth = navWrap.outerWidth();
        var containerWidth = containerWrap.width();
        if (menuWidth + logoWidth > containerWidth) {
            headerWrap.addClass(classToAdd);
        } else {
            if (headerWrap.hasClass(classToAdd))
            {
                headerWrap.removeClass(classToAdd);
            }
        }

    }
    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-logo').text('');
            var text = '';
            if ((wp.customize.instance('theme_logo').get() !== '') || (to !== '') || (wp.customize.instance('blogname').get() !== '')) {
                text += '<a class="home-link" href="#" title="" rel="home">';
                if (wp.customize.instance('theme_logo').get() !== '') {
                    text += '<div class="header-logo "><img src="' + wp.customize.instance('theme_logo').get() + '" alt=""></div>';
                }

                text += '<div class="site-description">';
                text += '<h1 class="site-title';
                if (to !== '') {
                    text += ' empty-tagline';
                }
                text += '">' + wp.customize.instance('blogname').get() + '</h1>';
                if (to !== '') {
                    text += '<p class="site-tagline">' + to + '</p>';
                }
                text += '</div>';
                text += '</a>';
            }
            $('.site-logo').append(text);
            menu_align();
        });
    });
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.site-logo').text('');
            var text = '';
            if ((wp.customize.instance('theme_logo').get() !== '') || (wp.customize.instance('blogdescription').get() !== '') || (to !== '')) {
                text += '<a class="home-link" href="#" title="" rel="home">';
                if (wp.customize.instance('theme_logo').get() !== '') {
                    text += '<div class="header-logo "><img src="' + wp.customize.instance('theme_logo').get() + '" alt=""></div>';
                }

                text += '<div class="site-description">';
                text += '<h1 class="site-title';
                if (wp.customize.instance('blogdescription').get() !== '') {
                    text += ' empty-tagline';
                }
                text += '">' + to + '</h1>';
                if (wp.customize.instance('blogdescription').get() !== '') {
                    text += '<p class="site-tagline">' + wp.customize.instance('blogdescription').get() + '</p>';
                }
                text += '</div>';
                text += '</a>';
            }
            $('.site-logo').append(text);
            menu_align();
        });
    });
    wp.customize('header_textcolor', function (value) {
        value.bind(function (to) {
            $('.main-header .site-title').css('color', to);
        });
    });
    wp.customize('theme_facebook_link', function (value) {
        value.bind(function (to) {
            $('.main-header .social-profile').text('');
            $('.site-footer .social-profile').text('');
            var text = '';
            if (to !== '') {
                text += '<a href="' + to + '" class="button-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>';
            }
            if (wp.customize.instance('theme_twitter_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_twitter_link').get() + '" class="button-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>';
            }
            if (wp.customize.instance('theme_linkedin_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_linkedin_link').get() + '" class="button-linkedin" title="LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a>';
            }
            if (wp.customize.instance('theme_google_plus_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_google_plus_link').get() + '" class="button-google" title="Google +" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
            }
            $('.main-header .social-profile').append(text);
            $('.site-footer .social-profile').append(text);
        });
    });
    wp.customize('theme_twitter_link', function (value) {
        value.bind(function (to) {
            $('.main-header .social-profile').text('');
            $('.site-footer .social-profile').text('');
            var text = '';
            if (wp.customize.instance('theme_facebook_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_facebook_link').get() + '" class="button-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>';
            }
            if (to !== '') {
                text += '<a href="' + to + '" class="button-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>';
            }
            if (wp.customize.instance('theme_linkedin_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_linkedin_link').get() + '" class="button-linkedin" title="LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a>';
            }
            if (wp.customize.instance('theme_google_plus_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_google_plus_link').get() + '" class="button-google" title="Google +" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
            }
            $('.main-header .social-profile').append(text);
            $('.site-footer .social-profile').append(text);
        });
    });
    wp.customize('theme_linkedin_link', function (value) {
        value.bind(function (to) {
            $('.main-header .social-profile').text('');
            $('.site-footer .social-profile').text('');
            var text = '';
            if (wp.customize.instance('theme_facebook_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_facebook_link').get() + '" class="button-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>';
            }
            if (wp.customize.instance('theme_twitter_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_twitter_link').get() + '" class="button-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>';
            }
            if (to !== '') {
                text += '<a href="' + to + '" class="button-linkedin" title="LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a>';
            }
            if (wp.customize.instance('theme_google_plus_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_google_plus_link').get() + '" class="button-google" title="Google +" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
            }
            $('.main-header .social-profile').append(text);
            $('.site-footer .social-profile').append(text);
        });
    });
    wp.customize('theme_google_plus_link', function (value) {
        value.bind(function (to) {
            $('.main-header .social-profile').text('');
            $('.site-footer .social-profile').text('');
            var text = '';
            if (wp.customize.instance('theme_facebook_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_facebook_link').get() + '" class="button-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>';
            }
            if (wp.customize.instance('theme_twitter_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_twitter_link').get() + '" class="button-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>';
            }
            if (wp.customize.instance('theme_linkedin_link').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_linkedin_link').get() + '" class="button-linkedin" title="LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a>';
            }
            if (to !== '') {
                text += '<a href="' + to + '" class="button-google" title="Google +" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
            }
            $('.main-header .social-profile').append(text);
            $('.site-footer .social-profile').append(text);
        });
    });
    wp.customize('header_image', function (value) {
        value.bind(function (to) {
            if (to === '') {
                $('.header-image-wrapper').hide();
            } else {
                $('.header-image-wrapper').show();
                $('.header-image-wrapper .header-image').css('background-image', to);
            }
        });
    });
    wp.customize('header_textcolor', function (value) {
        value.bind(function (to) {
            if ('blank' == to) {
                $('.site-description').hide();
            } else {
                $('.site-description').show();
            }
        });
    });
    wp.customize('theme_phone_info', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.contact-info').text('');
            var text = '<ul class=" info-list">';
            if (wp.customize.instance('theme_location_info').get() !== '') {
                text += '<li class="address-wrapper">' + wp.customize.instance('theme_location_info').get() + '</li>';
            }
            if (to !== '') {
                text += '<li class="phone-wrapper">' + to + '</li>';
            }
            text += '</ul>';
            $('.contact-info').append(text);
        });
    });
    wp.customize('theme_location_info', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.contact-info').text('');
            var text = '<ul class=" info-list">';
            if (to !== '') {
                text += '<li class="address-wrapper">' + to + '</li>';
            }
            if (wp.customize.instance('theme_phone_info').get() !== '') {
                text += '<li class="phone-wrapper">' + wp.customize.instance('theme_phone_info').get() + '</li>';
            }

            text += '</ul>';
            $('.contact-info').append(text);
        });
    });
    wp.customize('theme_bigtitle_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.big-section .section-content').text('');
            if (to !== '') {
                text += '<h1 class="section-title">' + to + '</h1>';
            }
            if (wp.customize.instance('theme_bigtitle_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_bigtitle_description').get() + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_bigtitle_brandbutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_bigtitle_whitebutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.big-section .section-content').append(text);
        });
    });
    wp.customize('theme_bigtitle_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.big-section .section-content').text('');
            if (wp.customize.instance('theme_bigtitle_title').get() !== '') {

                text += '<h1 class="section-title">' + wp.customize.instance('theme_bigtitle_title').get() + '</h1>';
            }
            if (to !== '') {
                text += '<div class="section-description">' + to + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_bigtitle_brandbutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_bigtitle_whitebutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.big-section .section-content').append(text);
        });
    });
    wp.customize('theme_bigtitle_brandbutton_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.big-section .section-content').text('');
            if (wp.customize.instance('theme_bigtitle_title').get() !== '') {

                text += '<h1 class="section-title">' + wp.customize.instance('theme_bigtitle_title').get() + '</h1>';
            }
            if (wp.customize.instance('theme_bigtitle_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_bigtitle_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (to !== '' && wp.customize.instance('theme_bigtitle_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_brandbutton_url').get() + '" title="' + to + '" class="button">' + to + '</a> ';
            }
            if (wp.customize.instance('theme_bigtitle_whitebutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.big-section .section-content').append(text);
        });
    });
    wp.customize('theme_bigtitle_brandbutton_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.big-section .section-content').text('');
            if (wp.customize.instance('theme_bigtitle_title').get() !== '') {

                text += '<h1 class="section-title">' + wp.customize.instance('theme_bigtitle_title').get() + '</h1>';
            }
            if (wp.customize.instance('theme_bigtitle_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_bigtitle_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_bigtitle_brandbutton_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_bigtitle_whitebutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.big-section .section-content').append(text);
        });
    });
    wp.customize('theme_bigtitle_whitebutton_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.big-section .section-content').text('');
            if (wp.customize.instance('theme_bigtitle_title').get() !== '') {

                text += '<h1 class="section-title">' + wp.customize.instance('theme_bigtitle_title').get() + '</h1>';
            }
            if (wp.customize.instance('theme_bigtitle_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_bigtitle_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_bigtitle_brandbutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '</a> ';
            }
            if (to !== '' && wp.customize.instance('theme_bigtitle_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_whitebutton_url').get() + '" title="' + to + '" class="button white-button">' + to + '</a>';
            }

            text += '</div>';
            $('.big-section .section-content').append(text);
        });
    });
    wp.customize('theme_bigtitle_whitebutton_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.big-section .section-content').text('');
            if (wp.customize.instance('theme_bigtitle_title').get() !== '') {

                text += '<h1 class="section-title">' + wp.customize.instance('theme_bigtitle_title').get() + '</h1>';
            }
            if (wp.customize.instance('theme_bigtitle_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_bigtitle_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_bigtitle_brandbutton_label').get() !== '' && wp.customize.instance('theme_bigtitle_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_bigtitle_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_bigtitle_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_bigtitle_whitebutton_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_bigtitle_whitebutton_label').get() + '</a>';
            }

            text += '</div>';
            $('.big-section .section-content').append(text);
        });
    });
    wp.customize('theme_welcome_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.welcome-section .section-content').text('');
            if (to !== '') {
                text += '<h2 class="section-title">' + to + '</h2>';
            }
            if (wp.customize.instance('theme_welcome_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_welcome_description').get() + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_welcome_button_label').get() !== '' && wp.customize.instance('theme_welcome_button_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_welcome_button_url').get() + '" title="' + wp.customize.instance('theme_welcome_button_label').get() + '" class="button">' + wp.customize.instance('theme_welcome_button_label').get() + '</a> ';
            }
            text += '</div>';
            $('.welcome-section .section-content').append(text);
        });
    });
    wp.customize('theme_welcome_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.welcome-section .section-content').text('');
            if (wp.customize.instance('theme_welcome_title').get() !== '') {
                text += '<h2 class="section-title">' + wp.customize.instance('theme_welcome_title').get() + '</h2>';
            }
            if (to !== '') {
                text += '<div class="section-description">' + to + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_welcome_button_label').get() !== '' && wp.customize.instance('theme_welcome_button_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_welcome_button_url').get() + '" title="' + wp.customize.instance('theme_welcome_button_label').get() + '" class="button">' + wp.customize.instance('theme_welcome_button_label').get() + '</a> ';
            }
            text += '</div>';
            $('.welcome-section .section-content').append(text);
        });
    });
    wp.customize('theme_welcome_button_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.welcome-section .section-content').text('');
            if (wp.customize.instance('theme_welcome_title').get() !== '') {
                text += '<h2 class="section-title">' + wp.customize.instance('theme_welcome_title').get() + '</h2>';
            }
            if (wp.customize.instance('theme_welcome_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_welcome_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (to !== '' && wp.customize.instance('theme_welcome_button_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_welcome_button_url').get() + '" title="' + to + '" class="button">' + to + '</a> ';
            }
            text += '</div>';
            $('.welcome-section .section-content').append(text);
        });
    });
    wp.customize('theme_welcome_button_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.welcome-section .section-content').text('');
            if (wp.customize.instance('theme_welcome_title').get() !== '') {
                text += '<h2 class="section-title">' + wp.customize.instance('theme_welcome_title').get() + '</h2>';
            }
            if (wp.customize.instance('theme_welcome_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_welcome_description').get() + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_welcome_button_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_welcome_button_label').get() + '" class="button">' + wp.customize.instance('theme_welcome_button_label').get() + '</a> ';
            }
            text += '</div>';
            $('.welcome-section .section-content').append(text);
        });
    });
    wp.customize('theme_third_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.third-section .section-content').text('');
            if (to !== '') {
                text += '<h2 class="section-title">' + to + '</h2>';
            }
            if (wp.customize.instance('theme_third_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_third_description').get() + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_third_button_label').get() !== '' && wp.customize.instance('theme_third_button_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_third_button_url').get() + '" title="' + wp.customize.instance('theme_third_button_label').get() + '" class="button">' + wp.customize.instance('theme_third_button_label').get() + '</a> ';
            }
            text += '</div>';
            $('.third-section .section-content').append(text);
        });
    });
    wp.customize('theme_third_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.third-section .section-content').text('');
            if (wp.customize.instance('theme_third_title').get() !== '') {
                text += '<h2 class="section-title">' + wp.customize.instance('theme_third_title').get() + '</h2>';
            }
            if (to !== '') {
                text += '<div class="section-description">' + to + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_third_button_label').get() !== '' && wp.customize.instance('theme_third_button_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_third_button_url').get() + '" title="' + wp.customize.instance('theme_third_button_label').get() + '" class="button">' + wp.customize.instance('theme_third_button_label').get() + '</a> ';
            }
            text += '</div>';
            $('.third-section .section-content').append(text);
        });
    });
    wp.customize('theme_third_button_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.third-section .section-content').text('');
            if (wp.customize.instance('theme_third_title').get() !== '') {
                text += '<h2 class="section-title">' + wp.customize.instance('theme_third_title').get() + '</h2>';
            }
            if (wp.customize.instance('theme_third_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_third_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (to !== '' && wp.customize.instance('theme_third_button_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_third_button_url').get() + '" title="' + to + '" class="button">' + to + '</a> ';
            }
            text += '</div>';
            $('.third-section .section-content').append(text);
        });
    });
    wp.customize('theme_third_button_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.third-section .section-content').text('');
            if (wp.customize.instance('theme_third_title').get() !== '') {
                text += '<h2 class="section-title">' + wp.customize.instance('theme_third_title').get() + '</h2>';
            }
            if (wp.customize.instance('theme_third_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_third_description').get() + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_third_button_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_third_button_label').get() + '" class="button">' + wp.customize.instance('theme_third_button_label').get() + '</a> ';
            }
            text += '</div>';
            $('.third-section .section-content').append(text);
        });
    });

    wp.customize('theme_install_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.install-section .section-content').text('');
            if (to !== '') {
                text += '<h3 class="section-title">' + to + '</h3>';
            }
            if (wp.customize.instance('theme_install_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_install_description').get() + '</div>';
            }
            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_install_brandbutton_label').get() !== '' && wp.customize.instance('theme_install_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_install_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_install_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_install_whitebutton_label').get() !== '' && wp.customize.instance('theme_install_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_install_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_install_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.install-section .section-content').append(text);
        });
    });
    wp.customize('theme_install_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.install-section .section-content').text('');
            if (wp.customize.instance('theme_install_title').get() !== '') {

                text += '<h3 class="section-title">' + wp.customize.instance('theme_install_title').get() + '</h3>';
            }
            if (to !== '') {
                text += '<div class="section-description">' + to + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_install_brandbutton_label').get() !== '' && wp.customize.instance('theme_install_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_install_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_install_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_install_whitebutton_label').get() !== '' && wp.customize.instance('theme_install_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_install_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_install_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.install-section .section-content').append(text);
        });
    });
    wp.customize('theme_install_brandbutton_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.install-section .section-content').text('');
            if (wp.customize.instance('theme_install_title').get() !== '') {

                text += '<h3 class="section-title">' + wp.customize.instance('theme_install_title').get() + '</h3>';
            }
            if (wp.customize.instance('theme_install_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_install_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (to !== '' && wp.customize.instance('theme_install_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_brandbutton_url').get() + '" title="' + to + '" class="button">' + to + '</a> ';
            }
            if (wp.customize.instance('theme_install_whitebutton_label').get() !== '' && wp.customize.instance('theme_install_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_install_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_install_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.install-section .section-content').append(text);
        });
    });
    wp.customize('theme_install_brandbutton_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.install-section .section-content').text('');
            if (wp.customize.instance('theme_install_title').get() !== '') {

                text += '<h3 class="section-title">' + wp.customize.instance('theme_install_title').get() + '</h3>';
            }
            if (wp.customize.instance('theme_install_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_install_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_install_brandbutton_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_install_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_install_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_install_whitebutton_label').get() !== '' && wp.customize.instance('theme_install_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_whitebutton_url').get() + '" title="' + wp.customize.instance('theme_install_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_install_whitebutton_label').get() + '</a>';
            }
            text += '</div>';
            $('.install-section .section-content').append(text);
        });
    });
    wp.customize('theme_install_whitebutton_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.install-section .section-content').text('');
            if (wp.customize.instance('theme_install_title').get() !== '') {

                text += '<h3 class="section-title">' + wp.customize.instance('theme_install_title').get() + '</h3>';
            }
            if (wp.customize.instance('theme_install_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_install_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_install_brandbutton_label').get() !== '' && wp.customize.instance('theme_install_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_install_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_install_brandbutton_label').get() + '</a> ';
            }
            if (to !== '' && wp.customize.instance('theme_install_whitebutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_whitebutton_url').get() + '" title="' + to + '" class="button white-button">' + to + '</a>';
            }

            text += '</div>';
            $('.install-section .section-content').append(text);
        });
    });
    wp.customize('theme_install_whitebutton_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.install-section .section-content').text('');
            if (wp.customize.instance('theme_install_title').get() !== '') {

                text += '<h3 class="section-title">' + wp.customize.instance('theme_install_title').get() + '</h3>';
            }
            if (wp.customize.instance('theme_install_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_install_description').get() + '</div>';
            }

            text += '<div class="section-buttons">';
            if (wp.customize.instance('theme_install_brandbutton_label').get() !== '' && wp.customize.instance('theme_install_brandbutton_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_install_brandbutton_url').get() + '" title="' + wp.customize.instance('theme_install_brandbutton_label').get() + '" class="button">' + wp.customize.instance('theme_install_brandbutton_label').get() + '</a> ';
            }
            if (wp.customize.instance('theme_install_whitebutton_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_install_whitebutton_label').get() + '" class="button white-button">' + wp.customize.instance('theme_install_whitebutton_label').get() + '</a>';
            }

            text += '</div>';
            $('.install-section .section-content').append(text);
        });
    });
    wp.customize('theme_features_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.features-section .section-title').text('');
            if (to !== '') {
                if ($('.features-section .section-title').length) {
                    $('.features-section .section-title').text(to);
                } else {
                    $('.features-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_features_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.features-section .section-description').text('');
            if (to !== '') {
                if ($('.features-section .section-description').length) {
                    $('.features-section .section-description').text(to);
                } else {
                    if ($('.features-section .section-title').length) {
                        $('.features-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.features-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_portfolio_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.portfolio-section .section-title').text('');
            if (to !== '') {
                if ($('.portfolio-section .section-title').length) {
                    $('.portfolio-section .section-title').text(to);
                } else {
                    $('.portfolio-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_portfolio_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.portfolio-section .section-description').text('');
            if (to !== '') {
                if ($('.portfolio-section .section-description').length) {
                    $('.portfolio-section .section-description').text(to);
                } else {
                    if ($('.portfolio-section .section-title').length) {
                        $('.portfolio-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.portfolio-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_portfolio_button_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.portfolio-section .section-buttons').text('');
            if (wp.customize.instance('theme_portfolio_button_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_portfolio_button_label').get() + '" class="button white-button">' + wp.customize.instance('theme_portfolio_button_label').get() + '</a>';
            }
            $('.portfolio-section .section-buttons').append(text);
        });
    });
    wp.customize('theme_portfolio_button_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.portfolio-section .section-buttons').text('');
            if (wp.customize.instance('theme_portfolio_button_url').get() !== '' && to !== '') {
                text += '<a href="' + wp.customize.instance('theme_portfolio_button_url').get() + '" title="' + to + '" class="button white-button">' + to + '</a>';
            }
            $('.portfolio-section .section-buttons').append(text);
        });
    });
    wp.customize('theme_plan_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.plan-section .section-title').text('');
            if (to !== '') {
                if ($('.plan-section .section-title').length) {
                    $('.plan-section .section-title').text(to);
                } else {
                    $('.plan-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_plan_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.plan-section .section-description').text('');
            if (to !== '') {
                if ($('.plan-section .section-description').length) {
                    $('.plan-section .section-description').text(to);
                } else {
                    if ($('.plan-section .section-title').length) {
                        $('.plan-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.plan-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_accent_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.accent-section .section-subcontent').text('');
            if (to !== '') {
                text += '<h3 class="section-title">' + to + '</h3>';
            }
            if (wp.customize.instance('theme_accent_description').get() !== '') {
                text += '<div class="section-description">' + wp.customize.instance('theme_accent_description').get() + '</div>';
            }
            $('.accent-section .section-subcontent').append(text);
        });
    });
    wp.customize('theme_accent_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.accent-section .section-subcontent').text('');
            if (wp.customize.instance('theme_accent_title').get() !== '') {
                text += '<h3 class="section-title">' + wp.customize.instance('theme_accent_title').get() + '</h3>';
            }
            if (to !== '') {
                text += '<div class="section-description">' + to + '</div>';
            }
            $('.accent-section .section-subcontent').append(text);
        });
    });
    wp.customize('theme_accent_button_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.accent-section .section-buttons').text('');
            if (to !== '' && wp.customize.instance('theme_accent_button_url').get() !== '') {
                text += '<a href="' + wp.customize.instance('theme_accent_button_url').get() + '" title="' + to + '" class="button white-button">' + to + '</a> ';
            }
            $('.accent-section .section-buttons').append(text);
        });
    });
    wp.customize('theme_accent_button_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.accent-section .section-buttons').text('');
            if (wp.customize.instance('theme_accent_button_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_accent_button_label').get() + '" class="button white-button">' + wp.customize.instance('theme_accent_button_label').get() + '</a> ';
            }
            $('.accent-section .section-buttons').append(text);
        });
    });
    wp.customize('theme_team_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.team-section .section-title').text('');
            if (to !== '') {
                if ($('.team-section .section-title').length) {
                    $('.team-section .section-title').text(to);
                } else {
                    $('.team-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_team_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.team-section .section-description').text('');
            if (to !== '') {
                if ($('.team-section .section-description').length) {
                    $('.team-section .section-description').text(to);
                } else {
                    if ($('.team-section .section-title').length) {
                        $('.team-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.team-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_subscribe_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.subscribe-section .section-title').text('');
            if (to !== '') {
                if ($('.subscribe-section .section-title').length) {
                    $('.subscribe-section .section-title').text(to);
                } else {
                    $('.subscribe-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_subscribe_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.subscribe-section .section-description').text('');
            if (to !== '') {
                if ($('.subscribe-section .section-description').length) {
                    $('.subscribe-section .section-description').text(to);
                } else {
                    if ($('.subscribe-section .section-title').length) {
                        $('.subscribe-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.subscribe-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_lastnews_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.lastnews-section .section-title').text('');
            if (to !== '') {
                if ($('.lastnews-section .section-title').length) {
                    $('.lastnews-section .section-title').text(to);
                } else {
                    $('.lastnews-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_lastnews_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.lastnews-section .section-description').text('');
            if (to !== '') {
                if ($('.lastnews-section .section-description').length) {
                    $('.lastnews-section .section-description').text(to);
                } else {
                    if ($('.lastnews-section .section-title').length) {
                        $('.lastnews-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.lastnews-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_lastnews_button_url', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.lastnews-section .section-buttons').text('');
            if (wp.customize.instance('theme_lastnews_button_label').get() !== '' && to !== '') {
                text += '<a href="' + to + '" title="' + wp.customize.instance('theme_lastnews_button_label').get() + '" class="button white-button">' + wp.customize.instance('theme_lastnews_button_label').get() + '</a>';
            }
            $('.lastnews-section .section-buttons').append(text);
        });
    });
    wp.customize('theme_lastnews_button_label', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.lastnews-section .section-buttons').text('');
            if (wp.customize.instance('theme_lastnews_button_url').get() !== '' && to !== '') {
                text += '<a href="' + wp.customize.instance('theme_lastnews_button_url').get() + '" title="' + to + '" class="button white-button">' + to + '</a>';
            }
            $('.lastnews-section .section-buttons').append(text);
        });
    });
    wp.customize('theme_testimonials_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.testimonials-section .section-title').text('');
            if (to !== '') {
                if ($('.testimonials-section .section-title').length) {
                    $('.testimonials-section .section-title').text(to);
                } else {
                    $('.testimonials-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_testimonials_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.testimonials-section .section-description').text('');
            if (to !== '') {
                if ($('.testimonials-section .section-description').length) {
                    $('.testimonials-section .section-description').text(to);
                } else {
                    if ($('.testimonials-section .section-title').length) {
                        $('.testimonials-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.testimonials-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_contactus_title', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.contact-section .section-title').text('');
            if (to !== '') {
                if ($('.contact-section .section-title').length) {
                    $('.contact-section .section-title').text(to);
                } else {
                    $('.contact-section .section-content').prepend('<h2 class="section-title">' + to + '</h2>');
                }
            }

        });
    });
    wp.customize('theme_contactus_description', function (value) {
        value.bind(function (to) {
            var text = '';
            $('.contact-section .section-description').text('');
            if (to !== '') {
                if ($('.contact-section .section-description').length) {
                    $('.contact-section .section-description').text(to);
                } else {
                    if ($('.contact-section .section-title').length) {
                        $('.contact-section .section-title').after('<div class="section-description">' + to + '</div>');
                    } else {
                        $('.contact-section .section-content').prepend('<div class="section-description">' + to + '</div>');

                    }
                }
            }
        });
    });
    wp.customize('theme_copyright', function (value) {
        value.bind(function (to) {
            var text = '<span class="copyright-date">' + $('.site-footer .copyright-date').text() + '</span>';
            $('.site-footer .copyright').text('');
            if (to !== '') {
                text += to;
            }
            $('.site-footer .copyright').html(text);
        });

    });

})(jQuery);
