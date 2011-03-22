<?php
/**
 * Template Name: Full width page
 *
 * Full widht page design.
 *
 *
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<body <?php body_class('subpage fullwidth'); ?>>

<?php get_template_part( 'base', 'page' ); ?>

<?php get_sidebar(); ?>

<?php if (get_settings('beatific_fullwidth_footer') == 'on') { get_footer(); } ?>
