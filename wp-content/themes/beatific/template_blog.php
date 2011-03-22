<?php
/**
 * Template Name: Blog
 *
 * Blog page design.
 *
 *
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<body <?php body_class('blog subpage'); ?>>

<?php get_template_part( 'loop', 'index' ); ?>

<?php require_once('sidebar_blog.php'); ?>

<?php if (get_settings('beatific_blog_footer') == 'on') { get_footer(); } ?>
