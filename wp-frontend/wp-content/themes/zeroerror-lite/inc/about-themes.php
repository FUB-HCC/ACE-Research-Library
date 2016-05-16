<?php
/**
 * Build Lite About Theme
 *
 * @package Build Lite
 */

//about theme info
add_action( 'admin_menu', 'zeroerror_lite_abouttheme' );
function zeroerror_lite_abouttheme() {    	
	add_theme_page( __('About Theme Info', 'zeroerror-lite'), __('About Theme Info', 'zeroerror-lite'), 'edit_theme_options', 'zeroerror_lite_guide', 'zeroerror_lite_mostrar_guide');   
} 

//guidline for about theme
function zeroerror_lite_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
?>

<style type="text/css">
@media screen and (min-width: 800px) {
.wrap-GT{ display:table;}
.gt-left {float:left; width: 55%; padding: 2%; margin:10px 0 0 15px; background-color:#fff;}
.heading-gt{font-size:18px; color:#0073AA; font-weight:bold; padding-bottom:10px; border-bottom:1px solid #ccc;}
.gt-right {float:right; width: 32%; padding:2%; margin:10px 0 0 15px; background-color:#fff;}
.clear{ clear:both;}
.wrap-GT ul{ margin:0; padding:0;}
.wrap-GT ul li{ list-style: disc inside none;}
.wrap-GT ul li:hover{ color:#0073AA; cursor:pointer;}
}
</style>

<div class="wrap-GT">
	<div class="gt-left">
   		   <div class="heading-gt">
			  <?php esc_attr_e('About Theme Info', 'zeroerror-lite'); ?>
		   </div>
          <p><?php esc_attr_e('Zeroerror Lite is a 100% responsive multipurpose WordPress theme with focus on corporate, industrial, commercial, and business websites.','zeroerror-lite'); ?></p>
<div class="heading-gt">Theme Features</div>
 

<div class="col-2">
  <h4><?php esc_attr_e('Theme Customizer', 'zeroerror-lite'); ?></h4>
  <div class="description"><?php esc_attr_e('The built-in customizer panel quickly change aspects of the design and display changes live before saving them.', 'zeroerror-lite'); ?></div>
</div>

<div class="col-2">
  <h4><?php esc_attr_e('Responsive Ready', 'zeroerror-lite'); ?></h4>
  <div class="description"><?php esc_attr_e('The themes layout will automatically adjust and fit on any screen resolution and looks great on any device. Fully optimized for iPhone and iPad.', 'zeroerror-lite'); ?></div>
</div>

<div class="col-2">
<h4><?php esc_attr_e('Cross Browser Compatible', 'zeroerror-lite'); ?></h4>
<div class="description"><?php esc_attr_e('Our themes are tested in all mordern web browsers and compatible with the latest version including Chrome,Firefox, Safari, Opera, IE8 and above.', 'zeroerror-lite'); ?></div>
</div>

<div class="col-2">
<h4><?php esc_attr_e('E-commerce', 'zeroerror-lite'); ?></h4>
<div class="description"><?php esc_attr_e('Fully compatible with WooCommerce plugin. Just install the plugin and turn your site into a full featured online shop and start selling products.', 'zeroerror-lite'); ?></div>
</div>

</div><!-- .gt-left -->
	
	<div class="gt-right">			
			<div style="font-weight:bold;">				
				<a href="<?php echo GRC_LIVE_DEMO; ?>" target="_blank"><?php esc_attr_e('Live Demo', 'zeroerror-lite'); ?></a> | 
				<a href="<?php echo GRC_PRO_THEME_URL; ?>"><?php esc_attr_e('Purchase Pro', 'zeroerror-lite'); ?></a> | 
				<a href="<?php echo GRC_THEME_DOC; ?>" target="_blank"><?php esc_attr_e('Theme Documentation', 'zeroerror-lite'); ?></a>
                <div style="height:5px"></div>
				<hr />  
                <ul>
                 <li><?php esc_attr_e('Theme Customizer', 'zeroerror-lite'); ?></li>
                 <li><?php esc_attr_e('Responsive Ready', 'zeroerror-lite'); ?></li>
                 <li><?php esc_attr_e('Cross Browser Compatible', 'zeroerror-lite'); ?></li>
                 <li><?php esc_attr_e('E-commerce', 'zeroerror-lite'); ?></li>
                 <li><?php esc_attr_e('Contact Form 7 Plugin Compatible', 'zeroerror-lite'); ?></li>  
                 <li><?php esc_attr_e('User Friendly', 'zeroerror-lite'); ?></li> 
                 <li><?php esc_attr_e('Translation Ready', 'zeroerror-lite'); ?></li>
                 <li><?php esc_attr_e('Many Other Plugins  Compatible', 'zeroerror-lite'); ?></li>   
                </ul>              
               
			</div>		
	</div><!-- .gt-right-->
    <div class="clear"></div>
</div><!-- .wrap-GT -->
<?php } ?>