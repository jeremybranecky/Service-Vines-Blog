<?php
/**
 * Template Name: Home
 *
 * Home page design.
 *
 *
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<body <?php body_class('home'); ?>>

<?php get_template_part( 'base', 'home' ); ?>

<?php if (get_settings('beatific_home_footer') == 'on') { get_footer(); } ?>
