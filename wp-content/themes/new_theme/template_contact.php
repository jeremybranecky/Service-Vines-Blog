<?php
/**
 * Template Name: Contact
 *
 * Contact page design.
 *
 *
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

get_header(); ?>

<body <?php body_class('contact subpage'); ?>>

<?php get_template_part( 'base', 'contact' ); ?>

<?php get_sidebar(); ?>

<?php if (get_settings('beatific_contact_footer') == 'on') { get_footer(); } ?>
