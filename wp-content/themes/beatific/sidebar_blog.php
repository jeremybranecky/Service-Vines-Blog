<!-- SIDEBAR_BLOG.PHP -->

	<div class="threecolumn">

	<?
		if ( is_active_sidebar( 'blog-widget-area' )) { 
			dynamic_sidebar( 'blog-widget-area' );
		} else {
			dynamic_sidebar( 'primary-widget-area' ); 
		} ?>
	</div>