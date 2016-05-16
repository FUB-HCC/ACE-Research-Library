<?php
/**
 * The template for displaying search forms in Zeroerror Lite
 *
 * @package Zeroerror Lite
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<?php /*<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'zeroerror-lite' ); ?></span>*/ ?>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'zeroerror-lite' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'zeroerror-lite' ); ?>">
</form>
