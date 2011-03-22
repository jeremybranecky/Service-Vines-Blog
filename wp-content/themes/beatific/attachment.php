<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- ATTACHMENT.PHP -->

<body <?php body_class('subpage fullwidth'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main"><!-- THIS IS THE HEADER PART OF THIS PAGE --></div>

		<div class="twocolumn">


			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2>
						<?php if ( ! empty( $post->post_parent ) ) : ?>
						<a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php esc_attr( printf( __( 'Return to %s' ), get_the_title( $post->post_parent ) ) ); ?>" rel="gallery"><?php printf( __( '%s: ' ), get_the_title( $post->post_parent ) ); ?></a>
						<?php endif; ?>
						<? the_title(); ?>
					</h2>

					<div class="meta">
						<?php
							printf(__('<span class="author">%2$s</span>' ),
								'author',
								sprintf( '<a class="url fn n" href="%1$s" title="%2$s">%3$s</a>',
									get_author_posts_url( get_the_author_meta( 'ID' ) ),
									sprintf( esc_attr__( 'View all posts by %s' ), get_the_author() ),
									get_the_author()
								)
							);
						?>
						<?php
							printf( __('<span class="date">%2$s</span>' ),
								'date',
								sprintf( '%2$s',
									esc_attr( get_the_time() ),
									get_the_date()
								)
							);
							if ( wp_attachment_is_image() ) {
								$metadata = wp_get_attachment_metadata();
								printf( __( 'Full size is %s pixels' ),
									sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
										wp_get_attachment_url(),
										esc_attr( __('Link to full-size image' ) ),
										$metadata['width'],
										$metadata['height']
									)
								);
							}
						?>
						<?php edit_post_link( __( 'Edit'  ), '<span class="edit">', '</span>' ); ?>
					</div>
					<br/>
					<div class="entry-content">
						<div class="entry-attachment">
					<?php if ( wp_attachment_is_image() ) :
						$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
						foreach ( $attachments as $k => $attachment ) {
							if ( $attachment->ID == $post->ID )
								break;
						}
						$k++;
						// If there is more than 1 image attachment in a gallery
						if ( count( $attachments ) > 1 ) {
							if ( isset( $attachments[ $k ] ) )
								// get the URL of the next image attachment
								$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
							else
								// or get the URL of the first image attachment
								$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
						} else {
							// or, if there's only 1 image attachment, get the URL of the image
							$next_attachment_url = wp_get_attachment_url();
						}
					?>
						<p class="attachment"><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
							echo wp_get_attachment_image( $post->ID, array( 960, 9999 ) ); // filterable image width with, essentially, no limit for image height.
						?></a></p>

						<div id="nav-below" class="navigation">
							<div class="nav-previous"><?php previous_image_link( false ); ?></div>
							<div class="nav-next"><?php next_image_link( false ); ?></div>
						</div><!-- #nav-below -->
					<?php else : ?>
						<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
					<?php endif; ?>
						</div><!-- .entry-attachment -->
						<div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>

					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:' ), 'after' => '</div>' ) ); ?>

					</div><!-- .entry-content -->

					<div class="entry-utility">
						<?php edit_post_link( __( 'Edit' ), ' <span class="edit">', '</span>' ); ?>
					</div><!-- .entry-utility -->
				</div><!-- #post-## -->

			<?php comments_template(); ?>
		
			<?php endwhile; ?>

			</div><!-- .twocolumn -->

<?php get_sidebar(); ?>

<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
