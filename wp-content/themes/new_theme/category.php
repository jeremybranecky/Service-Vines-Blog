<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- CATEGORY.PHP -->

<body <?php body_class('blog subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main">
			<!-- THIS IS THE HEADER PART OF THIS PAGE -->
		</div>
		
		<div class="twocolumn">

				<h2 class="page-title"><?php
					printf( __( 'Category Archives: %s' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				?></h2>
				<div class="content">
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';
					get_template_part( 'loop', 'category' );
				?>
				</div>

		</div><!-- .twocolumn -->

	<?php require_once('sidebar_blog.php'); ?>

	
<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
