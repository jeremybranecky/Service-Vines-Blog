<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */
 
get_header(); ?>

<!-- SEARCH.PHP -->

<body <?php body_class('blog subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main">
			<!-- THIS IS THE HEADER PART OF THIS PAGE -->
		</div>
		
		<div class="twocolumn">

			<?php if ( have_posts() ) : ?>
				<h2 class="page-title"><?php printf( __( 'Search results for: %s' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
			<?php else : ?>
				<div class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found' ); ?></h2>
					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
			<?php endif; ?>
		</div><!-- #container -->
		
<?php require_once('sidebar_blog.php'); ?>

<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
