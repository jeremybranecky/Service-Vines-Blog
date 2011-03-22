<?php
add_action('admin_init', 'qoate_settings_init' );
add_action('admin_menu', 'qoate_add_options_page');
add_action('admin_print_styles','add_qoate_dashboard_style');

function add_qoate_dashboard_style(){
wp_enqueue_style('qoate_admin_style', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/qoate_dashboard_layout.css');
}
 
// Add menu page
function qoate_add_options_page() {
	add_options_page('Qoate\'s Newsletter Signup Options', 'Qoate Newsletter Signup', 'manage_options', 'qoate-ns-options', 'qoate_create_options_page');
}

// Draw the menu page itself
function qoate_create_options_page() {
	?>
<div class="wrap">
	<h2>Qoate Newsletter Sign-Up Settings</h2>
	<div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<div class="postbox">
					<h3 class="hndle"><span>Qoate Newsletter Sign-up Configuration Settings</span></h3>
					<div class="inside">
					<p style="margin:5px">Here you can set the options for your newsletter sign-up. To find the right settings check the source of your 
					newsletter sign up form, and find the 'action' of the form, and the 'name' of the e-mail input field.</p>
					<form method="post" id="pierieliepierielo" action="options.php">
						<?php settings_fields('qoate_options');  $options = get_option('qoate_holder'); ?>
						<table class="form-table">
						<tr valign="top"><th scope="row">Newsletter Service</th>
							<td>
							<script type="text/javascript">
							function qoate_updateFields()
							{
							var service = document.getElementById('email_service').value;
							var do_with_name = document.getElementById('qoate_do_with_name').checked;
							var email_id_input = document.getElementById('qoate_email_id');
							switch(service)
							{
								case 'mailchimp':
									document.getElementById('qoate_form_action').readOnly=false;
									document.getElementById('qoate_form_action').value='';
									email_id_input.value='EMAIL';
									email_id_input.readOnly=true;
									document.getElementById('aweber_options').style.display='none';
									document.getElementById('phplist_options').style.display='none';
								break;
								case 'aweber':
									document.getElementById('qoate_form_action').value='http://www.aweber.com/scripts/addlead.pl';
									email_id_input.value='email';
									email_id_input.readOnly=true;
									document.getElementById('qoate_form_action').readOnly=true;
									document.getElementById('aweber_options').style.display='';
									document.getElementById('phplist_options').style.display='none';
								break;
								case 'icontact':
									document.getElementById('qoate_form_action').readOnly=false;
									document.getElementById('qoate_form_action').value='';
									email_id_input.value='fields_email';
									email_id_input.readOnly=true;
									document.getElementById('aweber_options').style.display='none';
									document.getElementById('phplist_options').style.display='none';
								break;
								case 'ymlp':
									document.getElementById('qoate_form_action').readOnly=false;
									document.getElementById('qoate_form_action').value='';
									email_id_input.value='YMP0';
									email_id_input.readOnly=true;
									document.getElementById('aweber_options').style.display='none';
									document.getElementById('phplist_options').style.display='none';
								break;
								case 'phplist':
									document.getElementById('qoate_form_action').readOnly=false;
									document.getElementById('qoate_form_action').value='';
									email_id_input.value='email';
									email_id_input.readOnly=true;
									document.getElementById('aweber_options').style.display='none';
									document.getElementById('phplist_options').style.display='';
								break;
								case 'other':
									email_id_input.value = '';
									email_id_input.readOnly=false;
									document.getElementById('qoate_form_action').readOnly=false;
									document.getElementById('phplist_options').style.display='none';
									document.getElementById('aweber_options').style.display='none';
								break;
							}
							
						if(do_with_name==true) {
							var name_id_input = document.getElementById('qoate_name_id');
							document.getElementById('qoate_name_row').style.display='';
							if(service=='mailchimp') {
								name_id_input.value='NAME';
								name_id_input.readOnly=true;
							} else {
								name_id_input.value='';
								name_id_input.readOnly=false;
							}
						} else {
							document.getElementById('qoate_name_row').style.display='none';
						}
						}
					</script>
					<select name="qoate_holder[email_service]" id="email_service" onChange="javascript:qoate_updateFields();">
						<option value="mailchimp"<?php if($options['email_service'] == 'mailchimp') echo ' SELECTED';?> >MailChimp/FeedBlitz</option>
						<option value="icontact"<?php if($options['email_service'] == 'icontact') echo ' SELECTED';?> >iContact</option>
						<option value="aweber"<?php if($options['email_service'] == 'aweber') echo ' SELECTED';?> >Aweber</option>
						<option value="phplist"<?php if($options['email_service'] == 'phplist') echo ' SELECTED';?> >PHPList</option>
						<option value="ymlp"<?php if($options['email_service'] == 'ymlp') echo ' SELECTED';?> >YMLP</option>
						<option value="other"<?php if($options['email_service'] == 'other') echo ' SELECTED';?> >Other/Advanced</option></select></td>
				</tr>
				<tr valign="top"><th scope="row">Newsletter form action</th>
					<td><input type="text" id="qoate_form_action" name="qoate_holder[ns_form_action]" value="<?php echo $options['ns_form_action']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Email identifier (name)</th>
					<td><input type="text" name="qoate_holder[ns_email_id]" id="qoate_email_id" value="<?php echo $options['ns_email_id']; ?>" READONLY /></td>
				</tr>
				<tr valign="top" id="aweber_options"<?php if($options['email_service'] != 'aweber') echo ' style="display:none" ';?> >
					<th scope="row">Aweber List name</th>
					<td><input type="text" name="qoate_holder[aweber_list_name]" value="<?php echo $options['aweber_list_name']; ?>" /></td>
				</tr>
				<tr valign="top" id="phplist_options"<?php if($options['email_service'] != 'phplist') echo ' style="display:none" ';?> >
					<th scope="row">PHPList list ID</th>
					<td><input size="2" type="text" name="qoate_holder[phplist_list_id]" value="<?php if(strlen($options['phplist_list_id']) > 0) { echo $options['phplist_list_id']; } else { echo 1; }; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Subscribe with name?</th>
					<td><input id="qoate_do_with_name" onChange="javascript:qoate_updateFields();" type="checkbox" value="1" name="qoate_holder[ns_do_with_name]"<?php if($options['ns_do_with_name']=='1') { echo ' CHECKED'; } ?> /></td>
				</tr>
				<tr valign="top" id="qoate_name_row"<?php if($options['ns_do_with_name'] !='1') { echo ' style="display:none;"';} ?>><th scope="row">Name identifier (name)</th>
					<td><input type="text" name="qoate_holder[ns_name_id]" id="qoate_name_id" value="<?php echo $options['ns_name_id']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Text to show after the checkbox</th>
					<td><input type="text" name="qoate_holder[ns_checkbox_txt]" value="<?php echo $options['ns_checkbox_txt']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Pre-check the checkbox?</th>
					<td><input type="checkbox" name="qoate_holder[ns_checkbox_precheck]" value="1"<?php if($options['ns_checkbox_precheck']=='1') { echo ' CHECKED'; } ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Add a checkbox to registration form?</th>
					<td><input type="checkbox" name="qoate_holder[do_on_register_form]" value="1"<?php if($options['do_on_register_form']=='1') { echo ' CHECKED'; } ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Hide checkbox for people who are already subscribed. <small>(works with a cookie, so dirty resolvement.)</small></th>
					<td><input type="checkbox" name="qoate_holder[hide_with_cookie]" value="1"<?php if($options['hide_with_cookie']=='1') { echo ' CHECKED'; } ?> /></td>
				</tr>
			</table>
				<p class="submit">
				<input onLoad="javascript:qoate_updateFields();" type="submit" class="button-primary" style="margin:5px;" value="<?php _e('Save Changes') ?>" />
				</p>
				
		
		</div>				
	</div>
			<div class="postbox">
				<h3 class="hndle"><span>Want to send some extra information to your newsletter service?.</span></h3>
				<div class="inside" style="padding:0.75%; width:98.5%;">
				<p><b>Advanced: </b>It could be that you want to send some extra information to your newsletter service, like the page/website where the user has signed up.
				Note that this will only gets stored if your newsletter service supports this. Use PHP with single quotes, check out <a href="http://qoate.com/wordpress-plugins/newsletter-sign-up">Qoate.com</a> for an example.
				<br /><small>PS: use IE to add input fields if neccessary. :/</small>
				</p>
			
				
				<script type="text/javascript">
				function addInputField()
				{
				var tbl = document.getElementById('extra_input_fields');
				var lastRow = tbl.rows.length;
				var nr = lastRow;
				var row = tbl.insertRow(lastRow);
				
				var cellLeft = row.insertCell(0);
				var input1 = document.createElement('input');
				input1.setAttribute('type','text');
				input1.setAttribute('name','qoate_holder[extra_input_name'+nr+']');
				input1.setAttribute('size',10);
				cellLeft.appendChild(input1);
				
				var cellRight = row.insertCell(1);
				var input2 = document.createElement('input');
				input2.setAttribute('type','text');
				input2.setAttribute('name','qoate_holder[extra_input_value'+nr+']');
				input2.setAttribute('size',20);
				cellRight.appendChild(input2);
				}
				
				// Firefox just Sucks when it comes to DOM...
				</script>
				
				<table class="form-table" name="extra_input_fields" id="extra_input_fields">
					<tr valign="top"><th scope="row"><b>Name</b></th>
						<th><b>Value</b></th>
					</tr>
				<tr valign="top"><th scope="row"><input type="text" size="10" name="qoate_holder[extra_input_name1]" value="<?php echo $options['extra_input_name1']; ?>" /></th><td><input type="text" size="20" name="qoate_holder[extra_input_value1]" value="<?php echo $options['extra_input_value1']; ?>"  /></td></tr>
				<?php 
				$i=2;
				for($i;$i<10;$i++) {
					if(strlen($options['extra_input_name'.$i])>0) {
						echo '<tr valign="top"><th scope="row"><input type="text" size="10" name="qoate_holder[extra_input_name'.$i.']" value="'.$options['extra_input_name'.$i].'" /></th><td><input type="text" size="20" name="qoate_holder[extra_input_value'.$i.']" value="'.$options['extra_input_value'.$i].'"  /></td></tr>';
					} else {
						break;
					}
				}
				?>
				</table>
				<?php eval($options ['extra_input_value1'].';'); ?>
				<a href="#extra-input-fields"onclick="javascript:addInputField();">Add an input field.</a>
				<p class="submit">
				<input type="submit" class="button-primary" style="margin:5px;" value="<?php _e('Save Changes') ?>" />
				</form>
				</p>
				</div>
			</div>
</div>
</div>
</div>
</div>
	<!-- Qoate Right Sidebar -->
		<?php include(QOATE_NS_PLUGIN_PATH.'qoate-right-sidebar.php'); ?>
</div>
<?php } ?>