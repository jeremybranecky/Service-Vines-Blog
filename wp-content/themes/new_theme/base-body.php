<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */
 ?>
 

 <!-- BASE-BODY.PHP -->

		<div class="container">
			<div class="header">
				<h1><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><? if (get_settings('beatific_logourl') != '') { ?><img src="<?=get_settings('beatific_logourl');?>" alt="<? bloginfo( 'name' ); ?>" /><?php } else bloginfo( 'name' ); ?></a>
<span class="description"><?php echo bloginfo( 'description' ); ?></span>
</h1>

				<div class="menu">
					<div class="top">
						<div class="search">
							<?php get_search_form(); ?>
						</div>
						<? if (get_settings('beatific_sitemap') != '') { ?><a href="<?=get_settings('beatific_sitemap');?>" id="sitemap" title="Got lost? Check out our sitemap">Sitemap</a> <? } ?>
					</div>
					
					<?
						if (get_settings('beatific_textmenu') == 'images')
							$navigation_menu = wp_nav_menu(array('container' => false, 'menu' => 'topmenu', 'menu_class' => 'menu images', 'fallback_cb' => '', 'echo' => false));
						else
							$navigation_menu = wp_nav_menu(array('container' => false, 'menu' => 'topmenu', 'menu_class' => 'menu text', 'fallback_cb' => '', 'echo' => false)); 
						
						$original = array('[img:', ']', 'a title="home"', 'a title="portfolio"', 'a title="blog"', 'a title="about"', 'a title="contact"');
						$fix	  = array('<img src="' . get_bloginfo(template_directory) . '/images/icons/menu_', '.png" alt="" />&nbsp;', 'a id="home"', 'a id="portfolio"', 'a id="blog"', 'a id="about"', 'a id="contact"');
						
						echo str_replace($original, $fix, $navigation_menu);
						
					?>
					
				</div>
			</div>