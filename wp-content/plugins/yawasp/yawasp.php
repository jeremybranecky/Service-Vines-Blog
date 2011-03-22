<?php
/*
Plugin Name: Yawasp - Yet Another WordPress Anti Spam Plugin
Plugin URI: http://www.svenkubiak.de/yawasp
Description: PLEASE NOTE: Yawasp has a successor called NoSpamNX (http://www.svenkubiak.de/nospamnx-en/). Development of Yawasp will be discontinued with the release of WordPress 2.8!!!
Version: 3.3
Author: Sven Kubiak, Lukas Sadzik
Author URI: http://www.svenkubiak.de

Copyright 2008 Sven Kubiak

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
global $wp_version;
define('YAWASPISWP26', version_compare($wp_version, '2.6', '>='));

Class Yawasp
{	
	var $catched = false;

	function createTable()
	{
		global $wpdb;
		
		//include correct file for maybe_create_table
		if (file_exists(ABSPATH . '/wp-admin/includes/upgrade.php')) {
			@require_once (ABSPATH . '/wp-admin/includes/upgrade.php');
		} elseif (file_exists(ABSPATH . WPINC . '/upgrade-functions.php')) {
			@require_once (ABSPATH . WPINC . '/upgrade-functions.php');
		} elseif (file_exists(ABSPATH . '/wp-admin/upgrades.php')) {
			@require_once (ABSPATH . '/wp-admin/upgrades.php');
		} else {
			echo "<div id='message' class='error fade'><p>".__('The required functions for creating the table could not be loaded.','yawasp')."</p></div>";
			return false;
		}		
		
		$query = "CREATE TABLE " . $wpdb->prefix . "yawasp (
					id int(11) unsigned NOT NULL auto_increment,
					ip varchar(20) NOT NULL default '',
					agent varchar(255) NOT NULL default '',
					until int(11) NOT NULL default '0',
					PRIMARY KEY (id)
				) TYPE=MyISAM;";		
		
		maybe_create_table($wpdb->prefix . 'yawasp', $query);
		
		return true;
	}	

	function yawasp()
	{
		//load language
		if (function_exists('load_plugin_textdomain'))
			load_plugin_textdomain('yawasp', PLUGINDIR.'/yawasp');
			
		//check if wordpress is at least 2.6
		if (YAWASPISWP26 != true){
			add_action('admin_notices', array(&$this, 'oldwp'));
			return;
		}
		
		//add wp actions	
		add_action('init', array(&$this, 'yawaspCheck'));		
		add_action('activate_yawasp/yawasp.php', array(&$this, 'activate'));			
		add_action('wp_head', array(&$this, 'yawaspStyle'));
		add_action('admin_menu', array(&$this, 'yawaspAdminMenu'));		
		add_action('rightnow_end', array(&$this, 'yawaspStats'));
	}

	function oldwp()
	{
		echo "<div id='message' class='error fade'><p>".__('Your WordPress is to old. Yawasp requires at least to WordPress 2.6.','yawasp')."</p></div>";
	}	

	function yawaspCheck()
	{													
		//check if logged in user does not require yawaspcheck
		if (get_option('yawas_checkuser') == 1 && is_user_logged_in()){
			//fill the original $_POST values so wordpress can handle them		
			$_POST['author'] 	= $_POST[$yawasp['author']];
			$_POST['email'] 	= $_POST[$yawasp['email']];
			$_POST['url'] 		= $_POST[$yawasp['url']];
			$_POST['comment'] 	= $_POST[$yawasp['comment']];	
		}
		else{	
			//get current field names from wp options
			$yawasp = get_option('yawasp');
			(!is_array($yawasp)) ? $yawasp = unserialize($yawasp) : false;	
	
			//check if we are in wp-comments-post.php
			if (basename($_SERVER['PHP_SELF']) == 'wp-comments-post.php'){
				//check if default comment or author form field is not empty, which probably points to spam bot
				if (!empty($_POST['comment']) || !empty($_POST['author']))
					$this->birdbrained('default');
					
				//do we have to check the ip?		
				if (get_option('yawasp_checkip') == 1){
					//check if ip is in database and block the spambot	
					if ($this->checkIp() === true)
						$this->birdbrained('ip');
				}				
			}
			
			//check if current comment form field is not empty, which probably points to real comment
			if (!empty($_POST[$yawasp['comment']])){
				//check if blank field was not submitted, which probably points to a spambot
				if (!array_key_exists($yawasp['blank'],$_POST)){
					$this->birdbrained('noblank');
				}			
				//check if blank field is not empty, which probably points to a spambot			
				elseif (!empty($_POST[$yawasp['blank']])){
					$this->birdbrained('emptyblank');
				}
				//fill the original $_POST values so wordpress can handle them		
				$_POST['author'] 	= $_POST[$yawasp['author']];
				$_POST['email'] 	= $_POST[$yawasp['email']];
				$_POST['url'] 		= $_POST[$yawasp['url']];
				$_POST['comment'] 	= $_POST[$yawasp['comment']];			
			}
		}
	}	
		
	function birdbrained($count)
	{
		//check if we already catched a spambot
		if ($this->catched === true)
			return;
	
		//save the spambots ip if enabled
		if (get_option('yawasp_checkip') == 1 && $this->checkIp() === true)
			$this->saveIp();					
		
		//count spambot
		switch ($count)
		{
			case 'default':
				update_option('yawasp_count_default', get_option('yawasp_count_default') + 1);
			break;
			case 'noblank':
				update_option('yawasp_count_noblank', get_option('yawasp_count_noblank') + 1);
			break;	
			case 'emptyblank':
				update_option('yawasp_count_emptyblank', get_option('yawasp_count_emptyblank') + 1);
			break;	
			case 'ip':
				update_option('yawasp_count_ip', get_option('yawasp_count_ip') + 1);
			break;								
		}
		
		//as of this point, we catched a spambot, so let the class know it (avoids double counting)
		$this->catched = true;
		 	
		//check in which mode we are and block, mark as spam or put in moderation queue
		if (get_option('yawasp_operate') == 'block'){
			wp_die(__('Sorry, but it seems you are a Spambot.','yawasp'));
		}
		else if (get_option('yawasp_operate') == 'mark'){			
			add_filter('pre_comment_approved', create_function('$a', 'return \'spam\';'));
		}
		else if (get_option('yawasp_operate') == 'moderate'){
			add_filter('pre_comment_approved', create_function('$a', 'return \'0\';'));
		}
		else{
			wp_die(__('Sorry, but it seems you are a Spambot.','yawasp'));
		}		
	}
	
	function generateNames()
	{
		$yawasp = array();
		
		//generate random names for form fields
		$yawasp['author'] 	= 'author-'.md5(uniqid(rand(), true));
		$yawasp['email'] 	= 'email-'.md5(uniqid(rand(), true));
		$yawasp['url'] 		= 'url-'.md5(uniqid(rand(), true));
		$yawasp['comment'] 	= 'comment-'.md5(uniqid(rand(), true));
		$yawasp['blank'] 	= 'author-'.md5(uniqid(rand(), true));

		$yawasp = serialize($yawasp);
		
		return $yawasp;
	}	

	function yawaspAdminMenu()
	{
		add_options_page('Yawasp', 'Yawasp', 8, 'yawasp', array(&$this, 'yawaspOptionPage'));	
	}
	
	function saveIp()
	{
		global $wpdb;
				
		//check if spambot is already in db		
		$result = $this->spambotInDb();
		
		//ip will be blocked occording to blocktime settings
		$currentime = time();		
		
		switch (get_option('yawasp_blocktime'))
		{
			case 0:
				$until = 2147483647;
			break;
			case 1:
				$until = $currentime + 3600;
			break;
			case 24:
				$until = $currentime + 86400;
			break;
			default:
				$until = $currentime + 86400;
		}
		
		//do we have the ip in the database? if not, update
		if ($result->id == 0){
			$wpdb->query("
				INSERT INTO " . $wpdb->prefix . "yawasp
				VALUES(
						NULL,
						'" . $_SERVER['REMOTE_ADDR'] . "',
						'" . md5($_SERVER['HTTP_USER_AGENT']) . "',
						'" . $until . "'
				)
			");
		}
		else{
			$wpdb->query("
				UPDATE " . $wpdb->prefix . "yawasp
				SET until = '" . $until . "'
				WHERE id = '". $result->id ."'
			");			
		}
		
		//clean up entries which will not be blocked any more
		$wpdb->query("
			DELETE FROM " . $wpdb->prefix . "yawasp WHERE until <= '" . $until . "'
		");
	}
	
	/*
	 * Returns true if ip adress is *not* database
	 */
	
	function checkIp()
	{					
		//get entries which matches ip and agent		
		$result = $this->spambotInDb();
		
		//do we have the entry in our database?
		if ($result->id == 0){
			return false;
		}
		//we have the entry but do we still block it?
		else if ($result->until < time()){
			return false;
		}
		else{
			return true;
		}		
	}
	
	function spambotInDb()
	{
		global $wpdb;
		
		//select entries which matches ip and agent
		$result = $wpdb->get_row("
				SELECT * 
				FROM " . $wpdb->prefix . "yawasp
				WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "'
				AND agent = '" . md5($_SERVER['HTTP_USER_AGENT']) . "'
		");
		
		return $result;		
	}
	
	function yawaspOptionPage()
	{	
		if (!current_user_can('manage_options'))
			wp_die(__('Sorry, but you have no permissions to change settings.','yawasp'));
			
		//set some variables for default form values
		$ipyes 	= '';
		$ipno 	= '';
		$blocktime0 = '';
		$blocktime1 = '';
		$blocktime24 = '';	
		$block = '';
		$mark = '';
		$moderate = '';		
		
		//do we have to reset the form names?
		if ($_POST['reset_names']){
			$this->updateNames();
			echo "<div id='message' class='updated fade'><p>".__('YAWASP Formfields were successfully regenerated.','yawasp')."</p></div>";
		}

		//do we have to change operating mode?
		if ($_POST['yawasp_mode'] == 'true'){
			switch($_POST['yawasp_operate'])
			{
				case 'block':
					update_option('yawasp_operate', 'block');
				break;
				case 'mark':
					update_option('yawasp_operate', 'mark');
				break;
				case 'moderate':
					update_option('yawasp_operate', 'moderate');
				break;
				default:
					update_option('yawasp_operate', 'block');		
			}
			($_POST['yawasp_checkuser'] == 1) ? update_option('yawasp_checkuser',1) : update_option('yawasp_checkuser',0);
			($_POST['yawasp_hiddenfield'] == 1) ? update_option('yawasp_strict',1) : update_option('yawasp_strict',0);
			echo "<div id='message' class='updated fade'><p>".__('YAWASP settings were saved successfully.','yawasp')."</p></div>";
		}
		
		//do we have to reset the counter?
		if ($_POST['reset_yawasp'] == 'true'){
			update_option('yawasp_count_default', 0);
			update_option('yawasp_count_noblank', 0);
			update_option('yawasp_count_emptyblank', 0);
			update_option('yawasp_count_ip', 0);
			echo "<div id='message' class='updated fade'><p>".__('YAWASP Counter was reseted successfully.','yawasp')."</p></div>";		
		}	
		
		//do we have to update the options?
		if ($_POST['yawasp_save_ip'] == 'true'){
			if($_POST['yawasp_ip'] == 1){
				if ($this->createTable() === true){
					update_option('yawasp_checkip', 1);					
				}
			} else if($_POST['yawasp_ip'] == 0){
				update_option('yawasp_checkip', 0);				
			}	
			
			//how long will the ips be blocked?
			switch($_POST['yawasp_blocktime'])
			{
				case 0:
					update_option('yawasp_blocktime', 0);
				break;
				case 1:
					update_option('yawasp_blocktime', 1);
				break;
				case 24;
					update_option('yawasp_blocktime', 24);
				break;
				default:				 	
					update_option('yawasp_blocktime', 0);
			}
			echo "<div id='message' class='updated fade'><p>".__('YAWASP settings were saved successfully.','yawasp')."</p></div>";
		}
		
		//does the user want to automaticly change the comments.php?
		if ($_POST['yawasp_commentsphp'] == 'do'){
			$this->changeCommentsPhp();
		}
		
		//do we have to undo the changes in the comments.php?
		if ($_POST['yawasp_commentsphp'] == 'undo'){
			$this->changeCommentsPhpUndo();
		}
		
		//set checked values for radio buttons
		(get_option('yawasp_checkip') == 1) ? $ipyes = 'checked' : $ipno = 'checked';	
		(get_option('yawasp_checkuser') == 1) ? $useryes = 'checked' : $userno = 'checked';
		(get_option('yawasp_strict') == 1) ? $strictyes = 'checked' : $strictno = 'checked';
		
		switch (get_option('yawasp_blocktime'))
		{
			case 0:
				$blocktime0 = 'checked';
			break;
			case 1:
				$blocktime1 = 'checked';
			break;	
			case 24:
				$blocktime24 = 'checked';
			break;
		}	

		switch (get_option('yawasp_operate'))
		{
			case 'block':
				$block = 'checked';
			break;
			case 'mark':
				$mark = 'checked';
			break;	
			case 'moderate':
				$moderate = 'checked';
			break;
		}
			
		//get counters
		$default 	= number_format_i18n(get_option('yawasp_count_default'));
		$noblank 	= number_format_i18n(get_option('yawasp_count_noblank'));
		$emptyblank = number_format_i18n(get_option('yawasp_count_emptyblank'));
		$ip 		= number_format_i18n(get_option('yawasp_count_ip'));
		
		//confirmation text for reseting the counter anf formfield names
		$confirm 	=	__('Are you sure you want to reset the counter?','yawasp');	
		$confirm2 	=	__('Are you sure you want to regenerate the Formfields?','yawasp');
			
		?>
		
		<div class="wrap">
			<h2><?php echo __('YAWASP Settings','yawasp'); ?></h2>
	    	<h3><?php echo __('Statistic','yawasp'); ?></h3>
	    	<table class="form-table">
	    		<tr>
					<th scope="row" valign="top">
					<?php echo __('Total','yawasp'); ?>	
					</th>
					<td>
					<?php $this->displayStats(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Detailed','yawasp'); ?>	
					</th>
					<td>
					<ul>
						<?php
						
						if ($default == 0 && $noblank == 0 && $emptyblank == 0 && $ip == 0){
							echo "<li>".__('No detailed statistic so far.','yawasp')."</li>";
						}
						else{
							if ($default > 0){
								echo "<li>";
								printf(__ngettext(
									"%s Spambot send the default author and/or comment field.",
									"%s Spambots send the default author and/or comment field.",
									$default, 'yawasp'), $default);
								echo "</li>";
							}							
							if ($noblank > 0){
								echo "<li>";
								printf(__ngettext(
									"%s Spambot did not send the hidden field.",
									"%s Spambots did not send the hidden field.",
									$noblank, 'yawasp'), $noblank);
								echo "</li>";						
							}
							if ($emptyblank > 0){
								echo "<li>";
								printf(__ngettext(
									"%s Spambot send the hidden field, but filled it out.",
									"%s Spambots send the hidden field, but filled it out.",
									$emptyblank, 'yawasp'), $emptyblank);	
								echo "</li>";														
							}
							if ($ip > 0){
								echo "<li>";
								printf(__ngettext(
									"%s Spambot was stopped because of their IP Address.",
									"%s Spambots was stopped because of their IP Address.",
									$ip, 'yawasp'), $ip);	
								echo "</li>";														
							}							
						}
								
						?>
					</ul>
					</td>						
	    		</tr>    	
		</table>
		<form action="options-general.php?page=yawasp" method="post" onclick="return confirm('<?php echo $confirm; ?>');">
			<input type="hidden" value="true" name="reset_yawasp">			
			<p class="submit">
			<input name="submit" value="<?php echo __('Reset Counter','yawasp'); ?>" type="submit" />
			</p>
		</form>
		<h3><?php echo __('Formfields','yawasp'); ?></h3>
	    <table class="form-table">						
	    		<tr>
					<th scope="row" valign="top"><?php echo __('Generate','yawasp'); ?></th>
					<td>
					<?php echo __('If you notice an increasing number of Spambots who make it to your blog, you might want to regenerate the names of the YAWASP Formfields.','yawasp'); ?>
					</td>
				</tr>
		</table>
		<form action="options-general.php?page=yawasp" method="post" onclick="return confirm('<?php echo $confirm2; ?>');">
		<input type="hidden" value="true" name="reset_names">
		<p class="submit">
		<input name="submit" value="<?php echo __('Regenerate Formfields','yawasp'); ?>" type="submit" />
		</p>	
		</form>	
		<h3><?php echo __('Operating mode','yawasp'); ?></h3>
		<p><?php echo __('By default all Spambots will be blocked. If you want to see what is blocked, select moderate or mark as spam. Catched Spambots will we be marked as Spam or put in moderation queue. Furthermore you can enable or disable if Yawasp should perfom its checks, if a user is logged in.','yawasp'); ?></p>
		<form action="options-general.php?page=yawasp" method="post">
	    <table class="form-table">						
	    		<tr>
					<th scope="row" valign="top"><?php echo __('Mode','yawasp'); ?></th>
					<td>					
					<input type="hidden" value="true" name="yawasp_mode">
					<input type="radio" name="yawasp_operate" <?php echo $block; ?> value="block"> <?php echo __('Block (recommended)','yawasp'); ?>
					<br />
					<input type="radio" <?php echo $mark; ?> name="yawasp_operate" value="mark"> <?php echo __('Mark as Spam (Akisment or similar required)','yawasp'); ?>
					<br />
					<input type="radio" <?php echo $moderate; ?> name="yawasp_operate" value="moderate"> <?php echo __('Moderate','yawasp'); ?>
					</td>									
				</tr>
	    		<tr>
					<th scope="row" valign="top"><?php echo __('Check logged in User','yawasp'); ?></th>
					<td>
					<input type="radio" name="yawasp_checkuser" <?php echo $useryes; ?> value="1"> <?php echo __('Yes','yawasp'); ?> <input type="radio" <?php echo $userno; ?> name="yawasp_checkuser" value="0"> <?php echo __('No','yawasp'); ?>	
					</td>									
				</tr>
		</table>
		<p><?php echo __('YAWASP adds an additional Formfield (hidden to the "real" user) to your comments.php. Most Spampots fall for this trap and fill it out blindly. To make this trap as good as we can, we have to make this hidden as close as it gets to a real Formfield. Therefore the id of this field is by default set to the value "Author". However, as ids can not have the same name twice, this is not strict HTML. Therefore you can decide on your own if you want more proctection, but not strict HTML or less protection, but strict HTML.','yawasp'); ?></p>	
		<table class="form-table">	
	    		<tr>
					<th scope="row" valign="top"><?php echo __('Name of the hidden Formfield','yawasp'); ?></th>					
					<td>
					<input type="radio" <?php echo $strictno; ?> name="yawasp_hiddenfield" value="0"> <?php echo __('Author (Higher protection, but not HTML conform)','yawasp');?><br /><input type="radio" name="yawasp_hiddenfield" <?php echo $strictyes; ?> value="1"> <?php echo __('Blank (Less protection, but HTML conform)','yawasp');  ?>	
					</td>									
				</tr>								
		</table>
		<p class="submit">
		<input name="submit" value="<?php echo __('Save','yawasp'); ?>" type="submit" />
		</p>		
		</form>		
		<h3><?php echo __('IP Lock','yawasp'); ?></h3>
		<p><?php echo __('If a Spambot is catched, the combination of his IP Adress and User-agent will be saved. You can lock this combination for 1 hour, 24 hours or indefinitely.','yawasp'); ?></p>
		<form action="options-general.php?page=yawasp" method="post">
	    <table class="form-table">						
	    		<tr>
					<th scope="row" valign="top"><?php echo __('Save IP Adress','yawasp'); ?></th>
					<td>
					<input type="radio" name="yawasp_ip" <?php echo $ipyes; ?> value="1"> <?php echo __('Yes','yawasp'); ?> <input type="radio" <?php echo $ipno; ?> name="yawasp_ip" value="0"> <?php echo __('No','yawasp'); ?>
					</td>									
				</tr>
	    		<tr>
					<th scope="row" valign="top"><?php echo __('Block time','yawasp'); ?></th>
					<td>
					<input type="radio" name="yawasp_blocktime" <?php echo $blocktime1; ?> value="1"> <?php echo __('1 hour','yawasp'); ?>
					<br />
					<input type="radio" name="yawasp_blocktime" <?php echo $blocktime24; ?> value="24"> <?php echo __('24 hours','yawasp'); ?>
					<br />
					<input type="radio" name="yawasp_blocktime" <?php echo $blocktime0; ?> value="0"> <?php echo __('Indefinitely','yawasp'); ?>
					</td>									
				</tr>				
		</table>
		<p class="submit">
		<input name="submit" value="<?php echo __('Save','yawasp'); ?>" type="submit" />
		</p>	
		<input type="hidden" value="true" name="yawasp_save_ip">
		</form>		
		<h3><?php echo __('Automatic template change','yawasp'); ?></h3>
		<p><?php echo __('You will find a Backup (comments.php.old) of your comments.php in your template folder.','yawasp'); ?></p>
		<form action="options-general.php?page=yawasp" method="post">
	    <table class="form-table">						
	    		<tr>
					<th scope="row" valign="top"><?php echo __('Changes in comments.php','yawasp'); ?></th>
					<td>					
					<input type="radio" name="yawasp_commentsphp" value="do"> <?php echo __('Change template','yawasp'); ?> <input type="radio" name="yawasp_commentsphp" value="undo"> <?php echo __('Undo changes','yawasp'); ?>
					</td>
				</tr>
		</table>	
		<p class="submit">
		<input name="submit" value="<?php echo __('Save','yawasp'); ?>" type="submit" />
		</p>	
		</form>			
	    <h3><?php echo __('Manual template change','yawasp'); ?></h3>
		<?php echo __('Manual instructions for changing your comments.php are available in english and german at the following websites:','yawasp'); ?>
		<ul>
			<li><a href="http://www.svenkubiak.de/yawasp/#installation"><?php echo __('Instructions in German','yawasp'); ?></a></li>
			<li><a href="http://www.svenkubiak.de/yawasp-en/#installation"><?php echo __('Instructions in English','yawasp'); ?></a></li>
		</ul>  
	    <p class="submit" /> 
	    </div>	
	    
	    <?php		
	}	
	
	function yawaspStyle()
	{	
		//build url to yawasp css file
		$yawaspcssurl = (get_bloginfo('wpurl')."/".PLUGINDIR."/yawasp/");
		
		//display link to yawasp css in wordpress header
		echo "<!-- yawaspcss -->\n";
		echo '<link rel="stylesheet" href="'.$yawaspcssurl.'yawaspStyle.css" type="text/css" media="screen" />';
		echo "\n<!-- /yawaspcss -->\n";
	}
	
	function yawaspSidebar()
	{
		//calculate total counter
		$counter = number_format_i18n(
		get_option('yawasp_count_default') + 
		get_option('yawasp_count_noblank') + 
		get_option('yawasp_count_emptyblank') +
		get_option('yawasp_count_ip')
		);
		
		echo '<a href="http://www.svenkubiak.de/yawasp">Yawasp</a>';
		printf(__ngettext(
			" has stopped %s birdbrained Spambot on this Blog!",
			" has stopped %s birdbrained Spambots on this Blog!",
			$counter, 'yawasp'), $counter);
	}
	
	function activate()
	{
		//add wp options
		add_option('yawasp', $this->generateNames(), '', 'yes');	
		add_option('yawasp_checkip', 0, '', 'yes');
		add_option('yawasp_count_default', 0, '', 'yes');
		add_option('yawasp_count_noblank', 0, '', 'yes');
		add_option('yawasp_count_emptyblank', 0, '', 'yes');
		add_option('yawasp_count_ip', 0, '', 'yes');
		add_option('yawasp_operate', 'block', '', 'yes');
		add_option('yawasp_blocktime', 1, '', 'yes');
		add_option('yawasp_checkuser', 1, '', 'yes');
		add_option('yawasp_strict', 0, '', 'yes');		
	}	
	
	function deactivate()
	{
		delete_option('yawasp');
		delete_option('yawasp_checkip');
		delete_option('yawasp_operate');
		delete_option('yawasp_blocktime');
		delete_option('yawasp_checkuser');
		delete_option('yawasp_strict');
	}
	
	function updateNames()
	{
		update_option('yawasp', $this->generateNames());
	}

	function yawaspStats()
	{	
		$this->displayStats(true);		
	}
	
	function displayStats($details=false)
	{
		//calculate total counter
		$counter = number_format_i18n(
		get_option('yawasp_count_default') + 
		get_option('yawasp_count_noblank') + 
		get_option('yawasp_count_emptyblank') +
		get_option('yawasp_count_ip')
		);
		
		echo "<p>";
		echo '<a href="http://www.svenkubiak.de/yawasp">Yawasp</a>';
		printf(__ngettext(
			" has stopped %s birdbrained Spambot.",
			" has stopped %s birdbrained Spambots.",
			$counter, 'yawasp'), $counter);

		//details only for dashboard
		if ($details === true){
			printf( __(
				' Click %s for a detailed statistic.','yawasp'),
				'<a href="options-general.php?page=yawasp">'.__('here','yawasp').'</a>');
		}
		echo "</p>";
	}

	function getName($field)
	{
		//get current field names from wp options
		$yawasp_name = get_option('yawasp');
		(!is_array($yawasp_name)) ? $yawasp_name = unserialize($yawasp_name) : false;
		
		switch ($field)
		{
			case 'author':
				echo $yawasp_name['author'];
			break;	
			case 'email':
				echo $yawasp_name['email'];
			break;	
			case 'url':
				echo $yawasp_name['url'];
			break;	
			case 'comment':
				echo $yawasp_name['comment'];
			break;							
			case 'blank':
			
				if (get_option('yawasp_strict') == 1){
					echo '<input type="text" name="'.$yawasp_name['blank'].'" id="blank" value="" class="catenasirpowotyp" />';
				}
				else{
					echo '<input type="text" name="'.$yawasp_name['blank'].'" id="author" value="" class="catenasirpowotyp" />';				
				}
				
			break;		
			default:
				return;
		}
	}	
	
	function changeCommentsPhp()
	{
		// declare a few variables
		$ret = false;	// the return variable
		
		$comments_php_rows = array();	// saves the lines of the new comments.php
		
		// define the fieldnames and types, we will change (is easyer to handle in an array) ^^
		// $fieldnames[fielname] => fieldtype
		$fieldnames = array(
			"author" => "input",
			"email" => "input",
			"url" => "input",
			"comment" => "textarea"
		);
		
		// We replace some strings, because they are hard to handle with the regular expressions
		$replaces = array(
			'<?' => md5('php_open'),
			'?>' => md5('php_close')
		);
		// Set filename and get absolute path to comments.php 
		$filename = 'comments.php';
		$comments_php = get_template_directory().'/'.$filename;
		// Now starts changing!
		if(file_exists($comments_php))
		{
			if($filelink = fopen($comments_php, 'r'))
			{
				$form_tag_found = false;
				$found_empty_field = false;
				$fields_found = array();
				$fields_allreadychanged = array();
				while($tmp = fgets($filelink)){
					$file_content[] = $tmp;
				}
				$n = 0;
				for($o = 0; $o < count($file_content); $o++)
				{
					// replace bad strings
					foreach($replaces as $what => $with)
					{
						$file_content[$o] = str_replace ($what, $with, $file_content[$o] );
					}
					
					// If we found the form-element
					if(true == $form_tag_found)
					{
						foreach($fieldnames as $fieldname => $fieldtype)
						{	// remember, we use an array for the fieldnames
							// Has we found a field we search for?
							if(ereg('(<'.$fieldtype.' .{0,})(name="'.$fieldname.'")',$file_content[$o]))
							{
								// generate replacement-string
								$new_name_attribut = "name=\"<?php if (class_exists('Yawasp')) { Yawasp::getName('".$fieldname."'); } else { echo \"".$fieldname."\"; } ?>\"";
								// replace string
								$file_content[$o] = ereg_replace('(<'.$fieldtype.' .{0,})(name="'.$fieldname.'")','\1'.$new_name_attribut,$file_content[$o]);
								// remember founded field
								$fields_found[ $fieldname] = true;
							}else
							{
								// check, if the field allready supports Yawasp. If so, mark the field as found!
								if(ereg("php if \(class_exists\('Yawasp'\)\) { Yawasp::getName\('".$fieldname."'\); } else { echo \"".$fieldname."\"; }",$file_content[$o]))
								{
									$fields_allreadychanged[ $fieldname] = true;
									$fields_found[ $fieldname] = true;
								}
							}
						}
					}
					
					//Undo replacement
					foreach($replaces as $with => $what)
					{
						$file_content[$o] = str_replace ($what, $with, $file_content[$o] );
					}
					// write current row in output-array for the new comments.php
					$rows[] = $file_content[$o];					
					
					// search for the form-element, while we didn't found it
					if(!$form_tag_found)
					{
						// check for the form-element
						if(ereg('<form .{0,}id=\"commentform\".{0,}>',$file_content[$o]))
						{
							$x = $o;
							while($x < count($file_content) and false == $found_empty_field){
								if(stristr ($file_content[$x],"<?php if (class_exists('Yawasp')) { Yawasp::getName('blank'); } ?>")){
									$found_empty_field = true;
									$form_tag_found = true;
								}
								$x++;
							}
							if(false == $found_empty_field){
								// we found it, and so we write the code for the hidden element one row after the form-element
								$rows[] = "<?php if (class_exists('Yawasp')) { Yawasp::getName('blank'); } ?>\n";
								// and we remember, that we found the form-element and search for fields can begin.
								$form_tag_found = true;
							}
						}
					}
				}			
				
				fclose($filelink);
				// Searching and preparing for output is over, now let's check, if we found everything:
				if(
					true == $form_tag_found 
					and isset($fields_found['author'])	and true == $fields_found['author']
					and isset($fields_found['email'])	and true == $fields_found['email']
					and isset($fields_found['url'])		and true == $fields_found['url']
					and isset($fields_found['comment'])	and true == $fields_found['comment']
				){
					
					if(
						true == $found_empty_field
						and isset($fields_allreadychanged['author'])	and true == $fields_allreadychanged['author']
						and isset($fields_allreadychanged['email'])		and true == $fields_allreadychanged['email']
						and isset($fields_allreadychanged['url'])		and true == $fields_allreadychanged['url']
						and isset($fields_allreadychanged['comment'])	and true == $fields_allreadychanged['comment']
					){
						$ret = true;
					}else
					{
					
						// Write the new File first as 'comments.php.new'
						//$file_rights = fileperms($file);	// This acutally don't work :/ It should save the permissions of the old file to assign it to the new file.
						if($filelink = fopen($comments_php.'.new',w))
						{
							flock($filelink,2);
							foreach($rows as $row){
								fputs($filelink,$row);
							}
							flock($filelink,3);
							fclose($filelink);
							//chmod($file,'0777');	// This acutally don't work :/ It should set the permissions of the new file to the same as the old file!
							// rename the files. Block error-reporting, that would print out.
							if(@rename($comments_php,$comments_php.'.old') and @rename($comments_php.'.new',$comments_php)){
								$ret = true;
							}
						}
					}
				}				
			}
		}
		if(true == $ret){
			echo "<div id='message' class='updated fade'><p>".__('Your comments.php was changed successfully. YAWASP is up and running!','yawasp')."</p></div>";
		}else{
			echo "<div id='message' class='error fade'><p>".__('Failed to change your comments.php. Please change manually','yawasp')."</p></div>";	
		}
	}
	
	function changeCommentsPhpUndo()
	{		
		$ret = false;
		$filename = 'comments.php';
		$comments_php = get_template_directory().'/'.$filename;
		if(file_exists($comments_php.'.old') and file_exists($comments_php))
		{
			if(@rename($comments_php,$comments_php.'.new') and @rename($comments_php.'.old',$comments_php))
			{
				if(unlink($comments_php.'.new'))
					$ret = true;
			}
		}
		if(true == $ret){
			 echo "<div id='message' class='updated fade'><p>".__('Changes in comments.php were successfully removed.','yawasp')."</p></div>";
		}else{
			 echo "<div id='message' class='error fade'><p>".__('Failed to remove changes in comments.php. Please remove manually','yawasp')."</p></div>";
		}		
	}	
}
//initalize class
if (class_exists('Yawasp'))
	$yawasp = new Yawasp();		
?>