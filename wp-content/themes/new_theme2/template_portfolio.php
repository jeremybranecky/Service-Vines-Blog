<?php
/**
 * Template Name: Portfolio
 *
 * Portfolio page design.
 *
 *
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<body <?php body_class('portfolio'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

	<div class="main"><!-- THIS IS THE HEADER PART OF THIS PAGE --></div>
	
	<div class="twocolumn">
		<h2><?php the_title(); ?></h2>
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="entry-content">
				<?php the_content( __( 'Continue reading this entry &#187' ) ); ?>
			</div><!-- .entry-content -->
		<?php endwhile; ?>
		
	<?php 
		$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
		query_posts('posts_per_page=6&paged=' . $page . '&post_type=portfolio_item');
		
		global $more;
		$more = 0;
	
		while ( have_posts() ) {
		
			the_post(); ?>
			<div class="portfolio_item" id="post-<?php the_ID(); ?>">
				<div class="thumbnail"><a href="<? the_permalink(); ?>" class="thumbnail"><? the_post_thumbnail(array(280, 9999), true); ?></a></div>
				<h3><a href="<? the_permalink(); ?>"><? the_title(); ?></a></h3>
				<?php the_content( __( ' ' ) ); ?>
			</div>
			
	<?	} ?>
	
		<div class="clear"></div>
		<? if (($wp_query -> max_num_pages) > 1) { ?>
		<div class="pages">
			<?php next_posts_link( __( '&#171; Previous page' ) ); ?>
			<?php previous_posts_link( __( 'Next page &#187;' ) ); ?>
		</div><!-- .pages -->
		<? } ?>
	
	</div>
	<div class="clear"></div>
	
	<?php get_sidebar(); ?>
	
<?php if (get_settings('beatific_portfolio_footer') == 'on') { get_footer(); } ?>
