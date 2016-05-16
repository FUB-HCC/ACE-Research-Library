<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if (post_password_required())
    return;
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h5 class="comments-title"><?php comments_number(__('No Comments', 'emmet-lite'), __('One Comment', 'emmet-lite'), __('% Comments', 'emmet-lite')); ?></h5>
        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 72,
                'callback' => 'mp_emmet_comment'
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php
        // Are there comments to navigate through?
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
            ?>
            <nav class="navigation comment-navigation">
                <ul>
                    <li class="nav-previous"><?php previous_comments_link(__('previous', 'emmet-lite')); ?></li>
                    <li class="nav-next"><?php next_comments_link(__('next', 'emmet-lite')); ?></li>
                </ul
            </nav> 
        <?php endif; // Check for comment navigation  ?>

        <?php if (!comments_open() && get_comments_number()) : ?>
            <p class="no-comments"><?php _e('Comments are closed.', 'emmet-lite'); ?></p>
        <?php endif; ?>

    <?php endif; // have_comments()  ?>
    <?php
    if (comments_open()) {
        $req      = get_option( 'require_name_email' );        
	$aria_req = ( $req ? " aria-required='true'" : '' );
        $comment_args = array(
            'fields' => apply_filters('comment_form_default_fields', array(
                'author' => '<div class="form-group comment-form-author">' .
                '<label for="author" class="control-label">' . __('Name','emmet-lite') . '</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '<input class="form-control" id="author" name="author" type="text" value="' .
                esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' />' .
                '</div><!-- #form-section-author .form-section -->',
                'email' => '<div class="form-group comment-form-email">' .
                '<label for="email" class="control-label">' . __('Email','emmet-lite') . '</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '<input class="form-control" id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' />' .
                '</div><!-- #form-section-email .form-section -->',
                'url' => '<div class="form-group comment-form-url"><label for="url" class="control-label">' .
                __('Website', 'emmet-lite') . '</label>' .
                '<input class="form-control" id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) .
                '" size="30" /></div>')),
            'comment_notes_after' => '',
            'comment_field' => '<div class="form-group comment-form-comment"><label for="comment" class="control-label">' . __('Comment', 'emmet-lite') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<br /><textarea rows="8" class="form-control" id="comment" name="comment" aria-required="true"></textarea></div>'
        );
        comment_form($comment_args);
    }
    ?>

</div><!-- #comments -->