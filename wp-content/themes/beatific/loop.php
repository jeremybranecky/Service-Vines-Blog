<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */
 
 global $query_string;
 
?>

<!-- LOOP.PHP -->

<?php if ( ! have_posts() ) { ?>
		<div class="content">
			<h2><?php _e( 'Not Found' ); ?></h2>
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.' ); ?></p>
			<?php get_search_form(); ?>
		</div>
<?php }
	
	while ( have_posts() ) : the_post(); ?>
		<div class="content" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="meta">
				<span class="date"><?php twentyten_posted_on(); ?></span>
				
				<span class="comments"><?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment' ), __( '% Comments' ) ); ?></span>
								
				<?php if ( count( get_the_category() ) ) : ?>
					<?php printf( __( '<span class="categories">%2$s</span>' ), 'categories', get_the_category_list( ', ' ) ); ?>
				<?php endif; ?>
				
				<?php printf( __( '<span class="tags">%2$s</span>' ), 'tags', $tags_list ); ?>
				
				<?php edit_post_link( __( 'Edit' ), '<span class="edit">', '</span>' ); ?>
			</div><!-- .meta -->

		<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<?php the_excerpt(); ?>
		<?php else : ?>
			<div class="entry-content">
				<?php the_content( __( 'Continue reading this entry &#187' ) ); ?>
			</div>
		<?php endif; ?>
	
		</div><!-- #post-## -->

		<?php comments_template( '', true ); ?>

<?php endwhile; // End the loop. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
		<div class="clear"></div>
		
<?php if ( ($wp_query -> max_num_pages) > 1 ) { ?>
		<div class="pages">
			<?php next_posts_link( __( '&#171; Previous page' ) ); ?>
			<?php previous_posts_link( __( 'Next page &#187;' ) ); ?>
		</div><!-- .pages -->
<?php } ?>