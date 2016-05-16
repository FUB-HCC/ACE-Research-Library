<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package Zeroerror Lite
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div class="headertop">
 <div class="container">
       <div class="left">
       <i class="fa fa-envelope-o"></i>
	   <?php if( get_theme_mod('contact_mail') !== ""){ ?>
         <a href="mailto:<?php echo sanitize_email(get_theme_mod('contact_mail','contact@company.com')); ?>"><?php echo get_theme_mod('contact_mail','contact@company.com'); ?></a>			
		<?php } ?>
         <span><i class="fa fa-phone"></i>
		 <?php if ( get_theme_mod('contact_no') !== "") { ?>
          <?php echo esc_attr_e( get_theme_mod( 'contact_no', __('+123 456 7890','zeroerror-lite'))); ?>       
		<?php } ?>
        </span>
       </div>
     <div class="right">
     <div class="social-icons">
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
</div>     
<div class="clear"></div>
 </div><!-- .container -->  
</div><!-- .headertop -->  

  <div class="header">
        <div class="container">
            <div class="logo">
                        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo('name'); ?></a></h1>
                        <p><?php bloginfo('description'); ?></p>
            </div><!-- logo -->
             <div class="toggle">
                <a class="toggleMenu" href="#"><?php _e('Menu','zeroerror-lite'); ?></a>
             </div><!-- toggle --> 
            <div class="sitenav">
                    <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
            </div><!-- site-nav -->
            <div class="clear"></div>
        </div><!-- container -->
  </div><!--.header -->

<?php if ( is_front_page() && ! is_home() ) { ?>
<!-- Slider Section -->
<?php for($sld=7; $sld<10; $sld++) { ?>
<?php if( get_theme_mod('page-setting'.$sld)) { ?>
<?php $slidequery = new WP_query('page_id='.get_theme_mod('page-setting'.$sld,true)); ?>
<?php while( $slidequery->have_posts() ) : $slidequery->the_post();
$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
$img_arr[] = $image;
$id_arr[] = $post->ID;
endwhile;
}
}
?>
<?php if(!empty($id_arr)){ ?>
<section id="home_slider">
<div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider">
	<?php 
	$i=1;
	foreach($img_arr as $url){ ?>
    <img src="<?php echo esc_url($url); ?>" title="#slidecaption<?php echo $i; ?>" />
    <?php $i++; }  ?>
</div>   
<?php 
$i=1;
foreach($id_arr as $id){ 
$title = get_the_title( $id ); 
$post = get_post($id); 
$content = apply_filters('the_content', substr(strip_tags($post->post_content), 0, 100)); 
?>                 
<div id="slidecaption<?php echo $i; ?>" class="nivo-html-caption">
<div class="slide_info">
<h2><?php echo $title; ?></h2>
<p><?php echo $content; ?></p>
<a class="slide_more" href="<?php the_permalink(); ?>"><?php _e('Read More', 'zeroerror-lite');?></a>
</div>
</div>      
<?php $i++; } ?>       
 </div>
<div class="clear"></div>        
</section>
<?php } else { ?>
<section id="home_slider">
<div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider">
    <img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider1.jpg" alt="" title="#slidecaption1" />
    <img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider2.jpg" alt="" title="#slidecaption2" />
    <img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider3.jpg" alt="" title="#slidecaption3" />
</div>                    
                  <div id="slidecaption1" class="nivo-html-caption">
                    <div class="slide_info">
                            <h2><?php _e('Multipurpose WordPress themes','zeroerror-lite'); ?></h2>
                            <p><?php _e('Nunc sed lorem pretium, volutpat tortor id, adipiscing sem. Sed bibendum quis augue nec porta. Ut molestie tortor pulvinar, faucibus justo vitae, interdum nunc. Vestibulum imperdiet nisl vel condimentum faucibus.','zeroerror-lite'); ?></p>
                           <a class="slide_more" href="#"><?php _e('Read More', 'zeroerror-lite');?></a>
                           
                    </div>
                    </div>
                    
                    <div id="slidecaption2" class="nivo-html-caption">
                        <div class="slide_info">
                                <h2><?php _e('High lavel Customization','zeroerror-lite'); ?></h2>
                                <p><?php _e('Nunc sed lorem pretium, volutpat tortor id, adipiscing sem. Sed bibendum quis augue nec porta. Ut molestie tortor pulvinar, faucibus justo vitae, interdum nunc. Vestibulum imperdiet nisl vel condimentum faucibus.','zeroerror-lite'); ?></p> 
                                <a class="slide_more" href="#"><?php _e('Read More', 'zeroerror-lite');?></a>                      
                        </div>
                    </div>
                    
                    <div id="slidecaption3" class="nivo-html-caption">
                        <div class="slide_info">
                                <h2><?php _e('User Friendly Theme Options','zeroerror-lite'); ?></h2>
                                <p><?php _e('Nunc sed lorem pretium, volutpat tortor id, adipiscing sem. Sed bibendum quis augue nec porta. Ut molestie tortor pulvinar, faucibus justo vitae, interdum nunc. Vestibulum imperdiet nisl vel condimentum faucibus.','zeroerror-lite'); ?></p>
                                <a class="slide_more" href="#"><?php _e('Read More', 'zeroerror-lite');?></a>
                        </div>
                    </div>
</div>
<div class="clear"></div>
</section>
<!-- Slider Section -->
<?php } ?>
        <?php } ?>
       
        
        <?php if ( is_front_page() && ! is_home() ) { ?>      
		 <section id="wrapsecond">
            	<div class="container">
                    <div class="services-wrap">                       
                        <?php for($p=1; $p<4; $p++) { ?>       
                        <?php if( get_theme_mod('page-column'.$p,false)) { ?>          
                            <?php $queryxxx = new WP_query('page_id='.get_theme_mod('page-column'.$p,true)); ?>				
                                    <?php while( $queryxxx->have_posts() ) : $queryxxx->the_post(); ?> 
                                    <div class="one_third <?php if($p % 3 == 0) { echo "last_column"; } ?>">                      
                                    <a href="<?php the_permalink(); ?>">
                                      <?php the_post_thumbnail( array(85,85, true) );?>
                                      <h4><?php the_title(); ?></h4>
                                    </a> 
                                    <?php echo zeroerror_lite_content(20); ?>                                   
                                    </div>
                                    <?php endwhile;
                                    wp_reset_query(); ?>
                                    
                        <?php } else { ?>
                                <div class="one_third <?php if($p % 3 == 0) { echo "last_column"; } ?>">                       
                                    <a href="#">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/services-icon<?php echo $p; ?>.png" alt="" />
                                    <h4><?php _e('Theme Featured','zeroerror-lite'); ?><?php echo $p; ?></h4>
                                    </a>
                                     <p><?php _e('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque augue eros, sit amet, consectetur adipiscing posuere elit.','zeroerror-lite'); ?></p>        
                                   
                                 </div>
                        <?php }} ?>  
                    <div class="clear"></div>  
               </div><!-- services-wrap-->
              <div class="clear"></div>
            </div><!-- container -->
       </section><div class="clear"></div>
       
       <section id="wrapfirst">
            	<div class="container">
                    <div class="welcomewrap">
					<?php if( get_theme_mod('page-setting1')) { ?>
                    <?php $queryvar = new WP_query('page_id='.get_theme_mod('page-setting1' ,true)); ?>
                    <?php while( $queryvar->have_posts() ) : $queryvar->the_post();?> 		
                    
                     <h1><?php the_title(); ?></h1>         
                     <?php the_content(); ?>
                     <?php the_post_thumbnail( array(570,380, true));?>                    
                     <div class="clear"></div>
                    <?php endwhile; } else { ?> 
                    
                    <h2><?php _e('Welcome to Our Website','zeroerror-lite'); ?></h2>
                    <p><?php _e('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sodales suscipit tellus, ut tristique neque suscipit a. Mauris tristique lacus quis leo imperdiet sed pulvinar dui fermentum. Aenean sit amet diam non tortor sagittis varius. Aenean at lorem nulla, sit amet interdum nibh. Mauris sit amet dictum turpis. Sed ut sapien magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. <br /> <br />

Aliquam gravida odio nec dui ornare tempus elementum lectus rhoncus. Suspendisse lobortis pellentesque orci, in sodales nisi pretium sit amet. Aenean vulputate, odio non euismod eleifend, magna nisl elementum lorem, ac venenatis nunc erat et metus. Nulla volutpat, urna eu congue venenatis, tellus odio hendrerit nibh, in commodo velit leo a ligula. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae aenean sit amet diam non tortor sagittis varius. Aenean at lorem nulla, sit amet interdum nibh. Mauris sit amet dictum turpis. ','zeroerror-lite'); ?></p>  
<img src="<?php echo get_template_directory_uri(); ?>/images/zeroerror-thumb1.png" alt=""/>                      
                    <?php } ?>
                      
               </div><!-- welcomewrap-->
              <div class="clear"></div>
            </div><!-- container -->
       </section><div class="clear"></div>   
       
		<?php }?>