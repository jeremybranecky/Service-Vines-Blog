<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php language_attributes(); ?>>
	<head>
<script type="text/javascript">
  var _kmq = _kmq || [];
  function _kms(u){
    setTimeout(function(){
      var s = document.createElement('script'); var f = document.getElementsByTagName('script')[0]; s.type = 'text/javascript'; s.async = true;
      s.src = u; f.parentNode.insertBefore(s, f);
    }, 1);
  }
  _kms('//i.kissmetrics.com/i.js');_kms('//doug1izaerwt3.cloudfront.net/5bdf7732c7239abe435d2f17ffb9765dc947c354.1.js');
</script>

		<script src="//cdn.optimizely.com/js/5937002.js"></script>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<title><?php
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		global $page, $paged;
		
		wp_title( '|', true, 'right' );
		
		// Add the blog name.
		bloginfo( 'name' );
		
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
		
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s' ), max( $paged, $page ) );
		
		?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
		<? if (get_settings('beatific_color') != 'blue') : ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?=get_bloginfo(template_directory); ?>/style_<?=get_settings('beatific_color');?>.css" />
		<? endif; ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<script src="<?=get_bloginfo(template_directory); ?>/js/jquery-1.4.1.min.js" type="text/javascript"></script>
		<script src="<?=get_bloginfo(template_directory); ?>/js/jquery.lightbox-0.5.pack.js" type="text/javascript"></script>
		<script src="<?=get_bloginfo(template_directory); ?>/js/cufon.js" type="text/javascript"></script>
		<script src="<?=get_bloginfo(template_directory); ?>/js/fontin_font.js" type="text/javascript"></script>
		<script src="<?=get_bloginfo(template_directory); ?>/js/beatific.js" type="text/javascript"></script>
		
		<?php wp_head(); ?>
		<meta name="google-site-verification" content="bfwrJXYJy8YT1PTQeH3ljMgvJEjnozbOLZDtt26AEZY" />
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20460278-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	</head>
