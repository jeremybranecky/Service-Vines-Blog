<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- PAGE.PHP -->

<body <?php body_class('blog subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main">
			<!-- THIS IS THE HEADER PART OF THIS PAGE -->
		</div>
		
		<div class="twocolumn">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if ( is_front_page() ) { ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php } else { ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php } ?>
						<div class="meta"><?php edit_post_link( __( 'Edit' ), '<span class="edit">', '</span>' ); ?></div>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</div><!-- #post-## -->

				<?php comments_template( '', true ); ?>

<?php endwhile; ?>

		</div><!-- #container -->

<?php require_once('sidebar_blog.php'); ?>

<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
