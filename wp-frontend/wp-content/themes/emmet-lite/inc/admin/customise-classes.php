<?php
/**
 * Emmet Customize Header Image Control class.
 *
 * @since 1.1.0
 *
 * @see WP_Customize_Header_Image_Control
 */
if (class_exists('WP_Customize_Header_Image_Control')):

    class MP_Emmet_Theme_Customize_Header_Image_Control extends WP_Customize_Header_Image_Control {

        public function render_content() {
            $this->print_header_image_template();
            $visibility = $this->get_current_image_src() ? '' : ' style="display:none" ';
            $width = absint(get_theme_support('custom-header', 'width'));
            $height = absint(get_theme_support('custom-header', 'height'));
            ?>


            <div class="customize-control-content">
                <p class="customizer-section-intro"><i>
                        <?php
                        _e('Note: this image is for pages with the "With Header Image" template.', 'emmet-lite');
                        ?>
                    </i><hr/></p>
            <p class="customizer-section-intro">
                <?php
                if ($width && $height) {
                    printf(__('While you can crop images to your liking after clicking <strong>Add new image</strong>, your theme recommends a header size of <strong>%s &times; %s</strong> pixels.', 'emmet-lite'), $width, $height);
                } elseif ($width) {
                    printf(__('While you can crop images to your liking after clicking <strong>Add new image</strong>, your theme recommends a header width of <strong>%s</strong> pixels.', 'emmet-lite'), $width);
                } else {
                    printf(__('While you can crop images to your liking after clicking <strong>Add new image</strong>, your theme recommends a header height of <strong>%s</strong> pixels.', 'emmet-lite'), $height);
                }
                ?>
            </p>

            <div class="current">
                <span class="customize-control-title">
                    <?php _e('Current header', 'emmet-lite'); ?>
                </span>
                <div class="container">
                </div>
            </div>
            <div class="actions">
                <?php /* translators: Hide as in hide header image via the Customizer */ ?>
                <button type="button"<?php echo $visibility ?> class="button remove"><?php _ex('Hide image', 'custom header','emmet-lite'); ?></button>
                <?php /* translators: New as in add new header image via the Customizer */ ?>
                <button type="button" class="button new"><?php _ex('Add new image', 'header image','emmet-lite'); ?></button>
                <div style="clear:both"></div>
            </div>
            <div class="choices">
                <span class="customize-control-title header-previously-uploaded">
                    <?php _ex('Previously uploaded', 'custom headers','emmet-lite'); ?>
                </span>
                <div class="uploaded">
                    <div class="list">
                    </div>
                </div>
                <span class="customize-control-title header-default">
                    <?php _ex('Suggested', 'custom headers','emmet-lite'); ?>
                </span>
                <div class="default">
                    <div class="list">
                    </div>
                </div>
            </div>
            </div>
            <?php
        }

    }

    endif;
/**
 * Emmet Customize Textarea class.
 *
 * @since 1.1.0
 *
 * @see WP_Customize_Header_Image_Control
 */
if (class_exists('WP_Customize_Control')) {

    class MP_Emmet_Theme_Customize_Textarea_Control extends WP_Customize_Control {

        public $type = 'textarea';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
            </label>
            <?php
        }

    }
}