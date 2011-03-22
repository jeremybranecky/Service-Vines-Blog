<?php
/*
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */
?>

<!-- FOOTER.PHP -->

	<?
	
		//Getting settings for Twitter.
		
		$beatific_twitter = get_settings('beatific_twitter');
	
	?>

		<div class="clear"></div>
	</div>
	
	<div class="footer">
		<div class="container">
			
			<? if ($beatific_twitter != '') : ?>
			<div class="twitter">
				<div class="head">
					<span class="blank"></span>
					<h3>Follow us on Twitter</h3>
					<a href="http://www.twitter.com/<?=$beatific_twitter;?>/" target="_blank">@<?=$beatific_twitter;?></a>
				</div>
				<div class="tweet"><p><div id="twitter_div"><ul id="twitter_update_list"></ul></div></p> </div>
				
				<script type="text/javascript">
					/* ADDING TWITTER SCRIPT AFTER BASE JQUERY LODED FOR BETTER CUFON GENERATION */
					$(document).ready(function() {
						var str = '<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"><';
						str += '/script><script type="text/javascript" src="http://twitter.com/statuses/user_timeline/' + '<?=$beatific_twitter;?>' + '.json?callback=twitterCallback2&amp;count=1"><';
						str += '/script>';
						$('div.twitter').append(str);
					});
				</script>
			
				<div class="bottom"><a href="http://www.twitter.com/<?=$beatific_twitter;?>/" target="_blank">http://www.twitter.com/<?=$beatific_twitter;?>/</a></div>
			</div>
			
			<? endif; ?>
			
			<div class="contact">
				
				<? //echo do_shortcode(get_settings('beatific_footer_contact')); ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>

<?php
	wp_footer();
?>
<p class="copyright">copyright <?php echo date("Y");?> Service Vines
</p>

</body>
</html>
