<?php
/*
Plugin Name: Qoate Newsletter Signup
Plugin URI: http://qoate.com/wordpress-plugins/newsletter-sign-up/
Description: All-in-one newsletter sign-up. Sign-up form widget, checkbox at comments.
Version: 2.0.1
Author: Danny van Kooten
Author URI: http://qoate.com
License: GPL2
*/

/*  Copyright 2010  Danny van Kooten  (email : danny@qoate.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('QOATE_NS_PLUGIN_PATH',WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__))); 

// Add the necessary actions
$qoate_options = get_option('qoate_holder');
add_action('comment_form','qoate_newsletter_signup',20);
add_action('comment_post', 'newsletter_signup', 50);
add_action('thesis_hook_after_comment_box','qoate_newsletter_signup',20);

if($qoate_options['do_on_register_form']=='1') {
	add_action('register_form','qoate_newsletter_signup',20);
	add_action('register_post', 'newsletter_signup', 50);
}

$qoate_show_checkbox=true;

/* The output of the checkbox */
function qoate_newsletter_signup($id='0') {
global $qoate_options;
global $qoate_show_checkbox;
if($qoate_options['hide_with_cookie']=='1' && $_COOKIE['qoate_subscriber']==true) $qoate_show_checkbox=false;
if($qoate_show_checkbox==true) {  
	echo '<p style="clear:both;"><input style="float:left; width:20px;" type="checkbox" value="true" name="qoate_newsletter"';
	if($qoate_options['ns_checkbox_precheck']=='1') { echo ' CHECKED'; }
	echo ' />';
	if(isset($qoate_options['ns_checkbox_txt'])) {
		echo $qoate_options['ns_checkbox_txt'];
	} else {
		echo 'Keep me up to date, sign me up for the newsletter!';
	}
	echo '</p>';
	$qoate_show_checkbox=false;
}
}

// Add settings link on plugin page
function qoate_ns_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=qoate-ns-options.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'qoate_ns_settings_link' );

/* The POST-request that happens after saving the comment in the WP database */
function rawurlencode_callback($value, $key)
{
	return "$key=" . rawurlencode($value);
}
function newsletter_signup($q_comment_post_ID)
{
if($_POST['qoate_newsletter']==true) {
global $qoate_options;
if(isset($qoate_options['ns_email_id']) && isset($qoate_options['ns_form_action'])) {
global $comment_author_email;
global $comment_author;
global $user_email;
if(strlen($comment_author_email) > 0) {
	$emailadress = $comment_author_email;
} else {
	$emailadress = $user_email;
}
		
		$variables = array (
				$qoate_options['ns_email_id']=>$emailadress
			);
		if($qoate_options['ns_do_with_name']=='1') {
			$variables[$qoate_options['ns_name_id']]=$comment_author;
		}
		$i=1;
		for($i;$i<10;$i++) {
			if(strlen($qoate_options['extra_input_name'.$i])>0) {
				eval('$ffdit = '.htmlentities($qoate_options["extra_input_value".$i]).';');
				$variables[$qoate_options['extra_input_name'.$i]]=$ffdit;
			} else {
				break;
			}
		}
		// if newsservice is Aweber, add some extra data.
		if($qoate_options['email_service']=='aweber') {
			$variables['listname'] = $qoate_options['aweber_list_name'];
			$variables['redirect'] = get_bloginfo('wpurl');
			$variables['meta_message'] = '1';
			$variables['meta_required'] = 'email';
		} elseif($qoate_options['email_service']=='phplist') {
			$variables['list['.$qoate_options['phplist_list_id'].']'] = 'signup';
			$variables['subscribe']="Subscribe";
			$variables["htmlemail"] = "1"; 
			$variables['emailconfirm']=$emailadress;
			$variables['makeconfirmed']='0';
		}
		//echo var_dump($variables); die();
		
		$encodedVariables = array_map ( 'rawurlencode_callback', $variables, array_keys($variables) );
		$postContent = join('&', $encodedVariables);
		$postContentLen = strlen($postContent);
		define ('CRLF', "\r\n");
		$streamCtx = stream_context_create (
		array (
			'http' => array (
			'method' => 'POST',
			'content' => $postContent,
			'header'  => "Content-Type: application/x-www-form-urlencoded" . CRLF . "Content-Length: $postContentLen" . CRLF
			)
		)
		);
		$fp = @fopen($qoate_options['ns_form_action'], 'r', FALSE, $streamCtx);
		
		if($qoate_options['hide_with_cookie']=='1') {
			setcookie('qoate_subscriber',true,time()+9999999);
		}
	} /* End isset form action & email identifier */
}
}

// Init plugin options to white list our options
function qoate_settings_init(){
	register_setting('qoate_options', 'qoate_holder');
}

// Load the backend of the plugin, only if needed.
if(is_admin()) {
      require("qoate-ns-dashboard.php");
}

class Custom_Search_Widget extends WP_Widget
{
	function Custom_Search_Widget()
	{
		$widget_ops = array('description' => 'A form where visitors can sign-up to your newsletter.');
		$this->WP_Widget('custom_search_widget', 'Newsletter Sign-Up', $widget_ops);
	}

	function form($instance)
	{
		$title = esc_attr($instance['title']);
		$text = esc_attr($instance['pre-text']);
		$email_text = esc_attr($instance['email_text']);
		$name_text = esc_attr($instance['name_text']);
		$subscribe_text = esc_attr($instance['subscribe_text']);
		$use_name_field = esc_attr($instance['use_name_field']);
		$where_label = esc_attr($instance['where_label']);
		global $qoate_options;
		if(strlen($qoate_options['ns_form_action']) == 0) echo "<p style='color:red;'>You haven't configured your newsletter settings yet. Want to <a href='options-general.php?page=qoate-ns-options'>do it now?</a></p>"
		?><p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('pre-text'); ?>">Pre-form Text: <input class="widefat" id="<?php echo $this->get_field_id('pre-text'); ?>" name="<?php echo $this->get_field_name('pre-text'); ?>" type="text" value="<?php echo $text; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('email_text'); ?>">Label for e-mail: <input class="widefat" id="<?php echo $this->get_field_id('email_text'); ?>" name="<?php echo $this->get_field_name('email_text'); ?>" type="text" value="<?php echo $email_text; ?>" /></label></p>
		<p>Show label inside or before input? <br />
		<span style="margin-left:30px;">Inside</span> <input id="<?php echo $this->get_field_id('where_label'); ?>" type="radio" name="<?php echo $this->get_field_name('where_label'); ?>" value="inside" <?php if($where_label=='inside') echo 'checked';?> /> 
		Before <input id="<?php echo $this->get_field_id('where_label'); ?>" type="radio" name="<?php echo $this->get_field_name('where_label'); ?>" value="before" <?php if($where_label=='before') echo 'checked';?> /></p>
		<p><label for="<?php echo $this->get_field_id('subscribe_text'); ?>">Subscribe Text: <input class="widefat" id="<?php echo $this->get_field_id('subscribe_text'); ?>" name="<?php echo $this->get_field_name('subscribe_text'); ?>" type="text" value="<?php echo $subscribe_text; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('use_name_field'); ?>">Use name field? <input style="margin-top:-15px;" class="widefat" id="<?php echo $this->get_field_id('use_name_field'); ?>" name="<?php echo $this->get_field_name('use_name_field'); ?>" type="checkbox" value="1" <?php if($use_name_field=='1') echo 'checked';?> /></label></p>
		<p><label for="<?php echo $this->get_field_id('name_text'); ?>">Label for name: <input class="widefat" id="<?php echo $this->get_field_id('name_text'); ?>" name="<?php echo $this->get_field_name('name_text'); ?>" type="text" value="<?php echo $name_text; ?>" /></label></p>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['pre-text'] = strip_tags($new_instance['pre-text']);
		$instance['email_text'] = strip_tags($new_instance['email_text']);
		$instance['name_text'] = strip_tags($new_instance['name_text']);
		$instance['subscribe_text'] = strip_tags($new_instance['subscribe_text']);
		$instance['use_name_field'] = strip_tags($new_instance['use_name_field']);
		$instance['where_label'] = strip_tags($new_instance['where_label']);
		return $instance;
	}

	function widget($args, $instance)
	{
		global $qoate_options;
		extract($args);
		
		$title	= ( $instance['title'] != '' ) ? $before_title . apply_filters('widget_title', esc_attr($instance['title'])) . $after_title : ''; // laat de title, en $before_title/$after_title, alleen zien als de widget title is ingevuld
		$use_name_field	= $instance['use_name_field'];
		$text = $instance['pre-text'];
		$subscribe_text	= ( $instance['subscribe_text'] != '' ) ? esc_attr($instance['subscribe_text']) : 'Subscribe';
		$name_label = ( $instance['name_text'] != '' ) ? esc_attr($instance['name_text']) : 'Name:';
		$email_label = ( $instance['email_text'] != '' ) ? esc_attr($instance['email_text']) : 'Emailadress:';
		$where_label = ( $instance['where_label'] != '' ) ? esc_attr($instance['where_label']) : 'before';
		
		$width = ((strlen($email_label) * 12) + 5);
		// output title & $before_widget
        echo $title . $before_widget;
		
		// output widget inhoud
		?>
        <form method="post" action="<?php echo $qoate_options['ns_form_action']; ?>">
			<?php if(strlen($text) > 0) echo "<p>$text</p>"; 
			if($use_name_field=='1') {?>
			<p><?php if($where_label=='before') { ?><label style="width:<?php echo $width; ?>px; display:inline-block;" for="qns_name"><?php echo $name_label; ?></label><?php } ?>
			<input id="qns_name" type="text" name="<?php echo $qoate_options['ns_name_id']; ?>" <?php if($where_label=='inside') { ?>value="<?php echo $name_label; ?>" <?php if ( $name_label != '' ) { ?> onblur="if(this.value=='') this.value='<?php echo $name_label; ?>'; return false;" onfocus="if(this.value!='') this.value=''; return false;"<?php } } ?> /></p><?php } ?>
			
        	<p><?php if($where_label=='before') { ?><label style="width:<?php echo $width; ?>px; display:inline-block;" for="qns_email"><?php echo $email_label; ?></label><?php } ?>
			<input id="qns_email" type="text" name="<?php echo $qoate_options['ns_email_id']; ?>" <?php if($where_label=='inside') { ?>value="<?php echo $email_label; ?>" <?php if ( $email_label != '' ) { ?> onblur="if(this.value=='') this.value='<?php echo $email_label; ?>'; return false;" onfocus="if(this.value!='') this.value=''; return false;"<?php } } ?> /></p>
        	<?php
			if($qoate_options['email_service']=='aweber') { ?>
				<input type="hidden" name="listname" value="<?php echo $qoate_options['aweber_list_name']; ?>" />
				<input type="hidden" name="redirect" value="<?php bloginfo('wpurl'); ?>" />
				<input type="hidden" name="meta_message" value="1" />
				<input type="hidden" name="meta_required" value="email" />
			<?php } elseif($qoate_options['email_service'] == 'phplist') { ?>
				<input type="hidden" name="list[<?php echo $qoate_options['phplist_list_id']; ?>]" value="signup" />
				<input type="hidden" name="subscribe" value="Subscribe" />
				<input type="hidden" name="htmlemail" value="1" />
				<input type="hidden" name="makeconfirmed" value="0" />
			<?php } ?>
			<p><input type="submit" value="<?php echo $subscribe_text; ?>" title="<?php echo $subscribe_text; ?>" class="submit" /></p>
        </form>
        <?php
		
		// output $after_widget
		echo $after_widget;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("Custom_Search_Widget");'));
