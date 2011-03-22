<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- SINGLE.PHP -->

<body <?php body_class('blog subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main">
			<!-- THIS IS THE HEADER PART OF THIS PAGE -->
		</div>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); 

	if (!(in_category(get_settings('beatific_portfolio_number')))) { //If the post is not a portfolio item ?>

			<div class="twocolumn">
				<div class="content">
					<h2><?php the_title(); ?></h2>
					<div class="meta">
						<span class="date"><?php twentyten_posted_on(); ?></span>
						<span class="comments"><?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment' ), __( '% Comments' ) ); ?></span>
						<?php edit_post_link( __( 'Edit' ), '<span class="edit">', '</span>' ); ?>
					</div>
					<?php the_content(); ?>
				</div>

				<?php comments_template( '', true ); ?>
				
	<? } else { //If the post is a portfolio item ?>

			<div class="twocolumn">
				<div class="portfolio_item big">
					<h3><?php the_title(); ?> <?php edit_post_link( __( 'Edit item' ), '<small>- ', '</small>' ); ?></h3>
					<span><a href="<?=get_settings('beatific_portfolio');?>">&#171; Back to Portfolio</a></span>
					<?php the_content('', FALSE, ''); ?>

				<?php comments_template( '', true ); ?>
				</div>
				
	<? } ?>

<?php endwhile; // end of the loop. ?>

			</div>

<?php
if (in_category(get_settings('beatific_portfolio_number'))) {
	get_sidebar();
} else { require_once('sidebar_blog.php'); } ?>

<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>

