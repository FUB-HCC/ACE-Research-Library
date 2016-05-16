<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Zeroerror Lite
 */
?>
<div id="footer-wrapper">
    	<div class="container">
             <div class="cols-4 widget-column-1"> 
                 <?php if ( get_theme_mod( 'about_title' ) !== "" ){  ?>
                   <h5><?php echo esc_html( get_theme_mod( 'about_title', __('About Us','zeroerror-lite'))); ?></h5>              
			     <?php } ?> 
                 
                 
                 
                  <?php if ( get_theme_mod( 'about_description' ) !== "" ){  ?>
                   <p><?php echo esc_html( get_theme_mod( 'about_description', __('Donec ut ex ac nulla pellentesque mollis in a enim. Praesent placerat sapien mauris, vitae sodales tellus venenatis ac. Suspendisse suscipit velit id ultricies auctor. Duis turpis arcu, aliquet sed sollicitudin sed, porta quis urna. Quisque velit nibh, egestas et erat a, vehicula interdum augue.','zeroerror-lite'))); ?></p>              
			     <?php } ?> 
            </div><!--end .widget-column-1-->                  
			         
             
             <div class="cols-4 widget-column-2"> 
              <?php if ( get_theme_mod( 'menu_title' ) !== "" ){  ?>
                   <h5><?php echo esc_html( get_theme_mod( 'menu_title', __('Main Navigation','zeroerror-lite'))); ?></h5>              
			     <?php } ?> 
             
                <div class="menu">
                  <?php wp_nav_menu(array('theme_location' => 'footer')); ?>
                </div>                        	
                       	
              </div><!--end .widget-column-2-->     
                      
                <div class="cols-4 widget-column-3">
                 <?php if ( get_theme_mod( 'recentposts_title' ) !== "" ){  ?>
                   <h5><?php echo esc_html( get_theme_mod( 'recentposts_title', __('Recent Posts','zeroerror-lite'))); ?></h5>              
			     <?php } ?> 
                 
                <?php $args = array( 'posts_per_page' => 2, 'post__not_in' => get_option('sticky_posts'), 'orderby' => 'date', 'order' => 'desc' );
                    query_posts( $args ); ?>
                    
                  <?php while ( have_posts() ) :  the_post(); ?>
                        <div class="recent-post">
                         <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                         <a href="<?php the_permalink(); ?>"><h6><?php the_title(); ?></h6></a>                         
                         <?php echo zeroerror_lite_content(12); ?>                         
                        </div>
                 <?php endwhile; ?>
                  
                    
                </div><!--end .widget-column-3-->
                
                <div class="cols-4 widget-column-4">
                <?php if ( get_theme_mod( 'contact_title' ) !== "" ){  ?>
                   <h5><?php echo esc_html( get_theme_mod( 'contact_title', __('Contact Info','zeroerror-lite'))); ?></h5>              
			     <?php } ?>                
                  
                  <?php if ( get_theme_mod( 'contact_add' ) !== "" ){  ?>
                   <p><?php echo esc_html( get_theme_mod( 'contact_add', __('100 King St Melbourne PIC 4000, Australia','zeroerror-lite'))); ?></p>              
			     <?php } ?>
                   
                   
                   
              <div class="phone-no">
			  
			   <?php if ( get_theme_mod('contact_no') !== "") { ?>
          <?php echo esc_html( get_theme_mod( 'contact_no', __('Phone: +123 456 7890','zeroerror-lite'))); ?>       
		<?php } ?><br  />
             
           <?php if( get_theme_mod('contact_mail') !== ""){ ?>
         <?php esc_html('Email: ', 'zeroerror-lite'); ?><a href="mailto:<?php echo sanitize_email(get_theme_mod('contact_mail','contact@company.com')); ?>"><?php echo get_theme_mod('contact_mail','contact@company.com'); ?></a>			
		<?php } ?>  
           
           </div>
                             	
					<div class="clear"></div>                
                 <div class="footer-icons">
					<?php if ( get_theme_mod('fb_link') !== "") { ?>
                   	<a title="facebook" class="fa fa-facebook" target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link','#facebook')); ?>"></a> 
                    <?php } ?>     
                    
                    <?php if ( get_theme_mod('twitt_link') !== "") { ?>
                   	<a title="twitter" class="fa fa-twitter" target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link','#twitter')); ?>"></a>
                    <?php } ?>      
                    
                    <?php if ( get_theme_mod('gplus_link') !== "") { ?>
                  	<a title="google-plus" class="fa fa-google-plus" target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link','#gplus')); ?>"></a>
                    <?php } ?>      
                    
                    <?php if ( get_theme_mod('linked_link') !== "") { ?> 
                  	<a title="linkedin" class="fa fa-linkedin" target="_blank" href="<?php echo esc_url(get_theme_mod('linked_link','#linkedin')); ?>"></a>
                    <?php } ?>
                </div>
              
                   
                </div><!--end .widget-column-4-->
                
                
            <div class="clear"></div>
        </div><!--end .container-->
        
        <div class="copyright-wrapper">
        	<div class="container">
            	<div class="copyright-txt">
				<?php if ( get_theme_mod('copyright_text') !== "") { ?>
               		<?php echo esc_html( get_theme_mod( 'copyright_text', __('','zeroerror-lite'))); ?>       
                <?php } ?>               
       			 </div>
                <div class="design-by">
				<?php if ( get_theme_mod('credit_link') !== "") { ?>
               		<?php echo esc_html( get_theme_mod( 'credit_link', __('Design and Developed by Grace Themes','zeroerror-lite'))); ?>       
                <?php } ?>
                </div>
                <div class="clear"></div>
            </div>            
        </div>
    </div>
<?php wp_footer(); ?>

</body>
</html>