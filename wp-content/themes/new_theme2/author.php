<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- AUTHOR.PHP -->

<body <?php body_class('blog subpage'); ?>>

<?php get_template_part( 'base', 'body' ); ?>

<div class="main"><!-- THIS IS THE HEADER PART OF THIS PAGE --></div>

<div class="twocolumn">

<?php
	if ( have_posts() )
		the_post();
?>

				<h2><?php printf( __( 'Author Archives: %s' ), "<a href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "'>" . get_the_author() . "</a>" ); ?></h2>

<?php
	if ( get_the_author_meta( 'description' ) ) : ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), 50 ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php printf( __( 'About %s' ), get_the_author() ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
						</div><!-- #author-description	-->
					</div><!-- #entry-author-info -->
<?php endif; ?>

<?php
	rewind_posts();
	get_template_part( 'loop', 'author' );
?>
		</div><!-- .twocolumn -->

<?php require_once('sidebar_blog.php'); ?>
<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
