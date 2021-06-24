<?php
/**
 * The template for displaying the comments.
 *
 * This contains both the comments and the comment form.
 */

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ( __('Please do not load this page directly. Thanks!', 'bloggingbox' ) );
 
if ( post_password_required() ) { ?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'bloggingbox' ); ?></p>
<?php
return;
}
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	<div id="comments">
		<h4 class="total-comments"><?php comments_number(__('No Responses', 'bloggingbox' ), __('One Response', 'bloggingbox' ),  '<span class="comm-number">%</span> '.__('Comments', 'bloggingbox' ) );?></h4>
		<ol class="commentlist">
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through ?>
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link() ?></div>
					<div class="alignright"><?php next_comments_link() ?></div>
				</div>
			<?php }
			
			wp_list_comments('callback=mts_comments');
			
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through ?>
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link() ?></div>
					<div class="alignright"><?php next_comments_link() ?></div>
				</div>
			<?php } ?>
		</ol>
	</div>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<div id="commentsAdd">
		<div id="respond" class="box m-t-6">
			<?php
			// Declare Vars.
			$comment_send     = esc_html__( 'Start Discussion', 'bloggingbox' );
			$comment_reply    = esc_html__( 'Leave a Comment', 'bloggingbox' );
			$comment_reply_to = esc_html__( 'Reply', 'bloggingbox' );
			$comment_author   = esc_html__( 'Name*', 'bloggingbox' );
			$comment_email    = esc_html__( 'Email*', 'bloggingbox' );
			$comment_body     = esc_html__( 'Your Thoughts on this...', 'bloggingbox' );
			$comment_url      = esc_html__( 'Website', 'bloggingbox' );
			$comment_cancel   = esc_html__( 'Cancel Reply', 'bloggingbox' );
			$comments_args    = [
				// Define Fields.
				'fields'               => [
					// Author field.
					'author'  => '<p class="comment-form-author"><input id="author" name="author" aria-required="true" placeholder="' . $comment_author . '" size="35"></input></p>',
					// Email Field.
					'email'   => '<p class="comment-form-email"><input id="email" name="email" aria-required="true" placeholder="' . $comment_email . '" size="35"></input></p>',
					// URL Field.
					'url'     => '<p class="comment-form-url"><input id="url" name="url" placeholder="' . $comment_url . '" size="35"></input></p>',
				],
				// Change the title of send button.
				'label_submit'         => $comment_send,
				// Change the title of the reply section.
				'title_reply'          => $comment_reply,
				// Change the title of the reply section.
				'title_reply_to'       => $comment_reply_to,
				// Cancel Reply Text.
				'cancel_reply_link'    => $comment_cancel,
				// Redefine your own textarea (the comment body).
				'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="6" aria-required="true" placeholder="' . $comment_body . '"></textarea></p>',
				// Message Before Comment.
				'comment_notes_before' => '',
				// Remove "Text or HTML to be displayed after the set of comment fields".
				'comment_notes_after'  => '',
				// Title Before.
				'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title">',
				// Title After.
				'title_reply_after' => '</h4>',
				// Submit Button ID.
				'id_submit'            => 'submit',
			];
			comment_form( $comments_args );
			?>
		</div>

	</div>
<?php
endif;
?>
