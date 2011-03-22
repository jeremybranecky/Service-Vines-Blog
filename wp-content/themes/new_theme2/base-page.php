<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */
?>

<!-- BASE-PAGE.PHP -->

<?php get_template_part( 'base', 'body' ); ?>

<div class="main"><!-- THIS IS THE HEADER PART OF THIS PAGE --></div>

<?php while ( have_posts() ) : the_post(); ?>

<div class="twocolumn">
	<div class="content">
		<h2><?php the_title(); ?></h2>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading this entry &#187' ) ); ?>
		</div><!-- .entry-content -->
	</div>
	
	<?php comments_template( '', true ); ?>
	
</div>

<?php endwhile; ?>