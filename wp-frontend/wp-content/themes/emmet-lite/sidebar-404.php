<?php
/**
 * The sidebar containing the secondary widget area
 *
 * Displays on posts and pages.
 *
 * If no active widgets are in this sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
if (is_active_sidebar('sidebar-404')) :
    ?>
    <aside id="sidebar">
        <div class="widget-area row">
            <?php dynamic_sidebar('sidebar-404'); ?>
        </div><!-- .widget-area -->
    </aside>
<?php endif; ?>