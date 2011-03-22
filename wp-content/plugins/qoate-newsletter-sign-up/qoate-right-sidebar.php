
<div class="postbox-container" style="width:20%;">
<div class="metabox-holder">	
<div class="meta-box-sortables">
										
			<div class="postbox qoate-right-box">				
				<h3 class="hndle"><span>Like this plugin?</span></h3>
				<div class="inside">
					<p>Consider the following options, please:</p>
					<ul>
						<li>Tell others about this plugin.</li>
						<li><a href="http://wordpress.org/extend/plugins/qoate-newsletter-sign-up/" target="_blank">Give a good rating on WordPress.org.</a></li>
						<li><a href="http://Qoate.com/donate/" target="_blank">Buy me a beer</a></li>
					</ul>				
				</div>
			</div>
			
			<div class="postbox qoate-right-box">
			<h3 class="hdnle"><span>Qoate updates..</span></h3>
			<div class="inside">
			<?php require_once(ABSPATH.WPINC.'/rss.php');  
			if ( $rss = fetch_rss( 'http://feeds.feedburner.com/qoate' ) ) {
				$content = '<ul>';
				$rss->items = array_slice( $rss->items, 0, 3 );
				foreach ( (array) $rss->items as $item ) {
					$content .= '<li>';
					$content .= '<a target="_blank" href="'.clean_url( $item['link'], $protocolls=null, 'display' ).'">'. htmlentities($item['title']) .'</a> ';
					$content .= '</li>';
				}
				$content .= '</ul>';
			} else {
				$content = '<p>No updates..</p>';
			} echo $content;?>
			</div>	
			</div>
			
			<div class="postbox qoate-right-box">
			<h3 class="hndle"><span>More tools & tips?</span></h3>
				<div class="inside">
					<p>Looking for more plugins or tips on how to improve your WordPress blog/website?
					Check out <a href="http://Qoate.com" target="_blank">Qoate.com</a>.</p>				
				</div>
			</div>
			
</div>
<br/><br/><br/>
</div>
</div>