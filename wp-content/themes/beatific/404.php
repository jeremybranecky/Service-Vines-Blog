<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- 404.PHP -->

<body <?php body_class('blog subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

		<div class="main"><!-- THIS IS THE HEADER PART OF THIS PAGE --></div>
		
		<div class="twocolumn">


			<div id="post-0" class="post error404 not-found">
				<h2 class="entry-title"><?php _e( '404 - Not Found' ); ?></h2>
				<div class="entry-content">
					<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->

		</div><!-- .twocolumn -->

		<?php require_once('sidebar_blog.php'); ?>

		
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>