/* global colorScheme, Color */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

(function (api) {
    api.controlConstructor.select = api.Control.extend({
        ready: function () {
            if ('theme_color_scheme' === this.id) {
                this.setting.bind('change', function (value) {
                    api('theme_color_primary').set(colorScheme[value].colors[0]);
                    api.control('theme_color_primary').container.find('.color-picker-hex').data('data-default-color', colorScheme[value].colors[0]).wpColorPicker('defaultColor', colorScheme[value].colors[0]);
                    api('theme_color_primary_light').set(colorScheme[value].colors[1]);
                    api.control('theme_color_primary_light').container.find('.color-picker-hex').data('data-default-color', colorScheme[value].colors[1]).wpColorPicker('defaultColor', colorScheme[value].colors[1]);
                    api('theme_color_primary_dark').set(colorScheme[value].colors[2]);
                    api.control('theme_color_primary_dark').container.find('.color-picker-hex').data('data-default-color', colorScheme[value].colors[2]).wpColorPicker('defaultColor', colorScheme[value].colors[2]);
                    api('theme_logo').set(colorScheme[value].colors[3]);
                    api.control('theme_logo').container.find('.attachment-thumb').attr('src', colorScheme[value].colors[3]);
                });
            }
        }
    });

})(wp.customize);
