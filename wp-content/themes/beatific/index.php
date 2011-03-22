<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<!-- INDEX.PHP -->

<body <?php body_class('blog subpage'); ?>>

	<?php get_template_part( 'base', 'body' ); ?>

	<div class="main"><!-- THIS IS THE HEADER PART OF THIS PAGE --></div>
	
	<div class="twocolumn">

	<?php get_template_part( 'loop', 'index' ); ?>
	
	</div>
	
	<?php require_once('sidebar_blog.php'); ?>
	
<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
