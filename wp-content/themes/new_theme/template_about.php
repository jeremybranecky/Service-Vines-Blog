<?php
/**
 * Template Name: About
 *
 * Blog page design.
 *
 *
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<body <?php body_class('aboutus subpage'); ?>>

<?php get_template_part( 'base', 'page' ); ?>

<?php get_sidebar(); ?>

<?php if (get_settings('beatific_about_footer') == 'on') { get_footer(); } ?>
