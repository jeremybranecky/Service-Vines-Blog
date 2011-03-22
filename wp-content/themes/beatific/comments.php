<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */
?>

<!-- COMMENT.PHP -->

			<div id="comments" class="comments">
		<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.' ); ?></p>
			</div><!-- #comments -->
		<?php
				return;
			endif;
		?>

		<?php if ( have_comments() ) : ?>
			<h2 id="comments-title">Comments</h2>

			<div class="previouscomments">
				<?php
					wp_list_comments( array( 'callback' => 'beatific_comment' ) );
				?>
			</div>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
				<div class="pages">
					<?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments' ) ); ?>
					<?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>' ) ); ?>
				</div><!-- .pages -->
			<?php endif; // check for comment navigation ?>
			
			<?php endif; // end have_comments() ?>
			
			<?php
			
			$fields =  array(
				'author' => '<div class="input"><input id="author" name="author" type="text" value="Name '. ( $req ? '(required)' : '' ) . '" size="30"' . $aria_req . ' /></div>',
				'email'  => '<div class="input"><input id="email" name="email" type="text" value="E-mail address '. ( $req ? '(required)' : '' ) . '" size="30"' . $aria_req . ' /></div>',
				'url'    => '<div class="input"><input id="url" name="url" type="text" value="Website" size="30" /></div>',
			);
			
			comment_form( array(
				'fields' => apply_filters('comment_form_default_fields', $fields ),
				'comment_field' => '<div class="textarea"><textarea id="comment" name="comment" class="message" cols="45" rows="8">Your message</textarea></div>',
				'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( '<p class="small">You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>'
			)); 
			?>
			
			</div>