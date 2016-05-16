<?php
/**
 * @package Zeroerror Lite
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

    
    <header class="entry-header">
        <h2 class="single_title"><?php the_title(); ?></h2>
    </header><!-- .entry-header -->
    
     <div class="postmeta">
            <div class="post-date"><?php echo get_the_date(); ?></div><!-- post-date -->
            <div class="post-comment"> &nbsp;|&nbsp; <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a></div> 
            <div class="clear"></div>         
    </div><!-- postmeta -->
    
    <?php 
        if (has_post_thumbnail() ){
			echo '<div class="post-thumb">';
            the_post_thumbnail();
			echo '</div>';
		}
        ?>

    <div class="entry-content">
         
		
        <?php the_content(); ?>
        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'zeroerror-lite' ),
            'after'  => '</div>',
        ) );
        ?>
        <div class="postmeta">
            <div class="post-categories"><?php the_category( __( ', ', 'zeroerror-lite' )); ?></div>
            <div class="post-tags"><?php the_tags( __('&nbsp;|&nbsp; Tags: ', 'zeroerror-lite')); ?> </div>
            <div class="clear"></div>
        </div><!-- postmeta -->
    </div><!-- .entry-content -->
   
    <footer class="entry-meta">
      <?php edit_post_link( __( 'Edit', 'zeroerror-lite' ), '<span class="edit-link">', '</span>' ); ?>
    </footer><!-- .entry-meta -->

</article>