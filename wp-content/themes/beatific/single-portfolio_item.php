<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- SINGLE.PHP -->

<body <?php body_class('portfolio subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main">
			<!-- THIS IS THE HEADER PART OF THIS PAGE -->
		</div>

		<div class="twocolumn">
		
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<div class="portfolio_item big" id="post-<?php the_ID(); ?>">
				<h2><? the_title(); ?></h2>
				<a href="javascript:history.go(-1)">&#171; Back to portfolio</a>
				<?
				
					$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
					$src = $src[0];
					
					if ($src) { ?> <a href="<?=$src;?>" class="gallery"> <? }
				
					the_post_thumbnail(array(610, 9999), true);
					
					if ($src) { ?> </a> <? }
				
				?>
				<div class="content"><?php the_content( __( ' ' ) ); ?></div>
			</div>
			
			<?php comments_template( '', true ); ?>
	
		<?php endwhile; // end of the loop. ?>
		
		</div>

<?php get_sidebar(); ?>

<?php if (get_settings('beatific_portfolio_footer') == 'on') { get_footer(); } ?>