<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>

</div><!-- #main -->
<?php if (get_page_template_slug() != 'template-landing-page.php' || is_search()): ?>
    <footer id="footer" class="site-footer">
        <a href="#" id="toTop" class="toTop"><i class="fa fa-angle-up"></i></a>
        <?php get_sidebar('footer'); ?>
        <div class="footer-inner">
            <div class="container">
                <?php
                $mp_emmet_facbook_link = esc_url( get_theme_mod('theme_facebook_link') );
                $mp_emmet_twitter_link = esc_url(  get_theme_mod('theme_twitter_link') );
                $mp_emmet_linkedin_link = esc_url( get_theme_mod('theme_linkedin_link') );
                $mp_emmet_google_plus_link = esc_url( get_theme_mod('theme_google_plus_link') );
                $mp_emmet_theme_copyright =get_theme_mod('theme_copyright');
                ?>
                <div class="social-profile type1 pull-right">
                    <?php if (get_theme_mod('theme_facebook_link', false) === false) : ?> 
                        <a href="#" class="button-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>
                    <?php else: ?>
                        <?php if (!empty($mp_emmet_facbook_link)): ?> 
                            <a href="<?php echo $mp_emmet_facbook_link; ?>" class="button-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (get_theme_mod('theme_twitter_link', false) === false) : ?> 
                        <a href="#" class="button-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>
                    <?php else: ?>
                        <?php if (!empty($mp_emmet_twitter_link)): ?>
                            <a href="<?php echo $mp_emmet_twitter_link; ?>" class="button-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (get_theme_mod('theme_linkedin_link', false) === false) : ?> 
                        <a href="#" class="button-linkedin" title="LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a>
                    <?php else: ?>      
                        <?php if (!empty($mp_emmet_linkedin_link)): ?>
                            <a href="<?php echo $mp_emmet_linkedin_link; ?>" class="button-linkedin" title="LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (get_theme_mod('theme_google_plus_link', false) === false) : ?> 
                        <a href="#" class="button-google" title="Google +" target="_blank"><i class="fa fa-google-plus-square"></i></a>
                    <?php else: ?>
                        <?php if (!empty($mp_emmet_google_plus_link)): ?>
                            <a href="<?php echo $mp_emmet_google_plus_link; ?>" class="button-google" title="Google +" target="_blank"><i class="fa fa-google-plus-square"></i></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <p class="copyright"><span class="copyright-date"><?php _e('&copy; Copyright', 'emmet-lite'); ?> <?php
                        $dateObj = new DateTime;
                        $year = $dateObj->format("Y");
                        echo $year;
                        ?> 
                    </span>
                    <?php
                    ?>
                      <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>" target="_blank"><?php bloginfo('name'); ?></a>
                      <?php printf(__('&#8226; Designed by', 'emmet-lite')); ?> <a href="<?php echo esc_url(__('http://www.getmotopress.com/', 'emmet-lite' )); ?>" rel="nofollow" title="<?php esc_attr_e('Premium WordPress Plugins and Themes', 'emmet-lite' ); ?>"><?php _e('MotoPress', 'emmet-lite'); ?></a>
                      <?php printf(__('&#8226; Proudly Powered by ',  'emmet-lite')); ?><a href="<?php echo esc_url(__('http://wordpress.org/', 'emmet-lite')); ?>"  rel="nofollow" title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'emmet-lite' ); ?>"><?php _e('WordPress',  'emmet-lite' ); ?></a>
                      <?php
                    ?>
                </p><!-- .copyright -->
            </div>
        </div>
    </footer>
<?php endif; ?>
</div>
<?php wp_footer(); ?>
</body>
</html>