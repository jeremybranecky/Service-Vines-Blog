<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- ARCHIVE.PHP -->

<body <?php body_class('blog subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main">
			<!-- THIS IS THE HEADER PART OF THIS PAGE -->
		</div>
		
		<div class="twocolumn">

<?php
	if ( have_posts() )
		the_post();
	?>

			<h2 class="page-title">
			<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: <span>%s</span>' ), get_the_date() ); ?>
			<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: <span>%s</span>' ), get_the_date('F Y') ); ?>
			<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: <span>%s</span>' ), get_the_date('Y') ); ?>
			<?php else : ?>
				<?php _e( 'Blog Archives' ); ?>
			<?php endif; ?>
			</h2>

			<?php
				rewind_posts();
				get_template_part( 'loop', 'archive' );
			?>
		</div><!-- .twocolumn -->

<?php require_once('sidebar_blog.php'); ?>
<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
