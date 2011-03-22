<?php
/**
 * @package WordPress
 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)
 * @since Beatific 1.0
 */

if ( ! isset( $content_width ) ) $content_width = 620;

if ( ! function_exists( 'beatific_setup' ) ) {

	add_action( 'after_setup_theme', 'beatific_setup' );

	function beatific_setup() {
		add_editor_style();
		
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size(280, 9999, false);
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'menus' );

		add_action("admin_init", "admin_init");
		add_action('save_post', 'save_featured');
		add_action( 'init', 'create_portfolio' );
		add_action('admin_menu', 'beatific_add_admin');

	}
}



// Registering the Top menu for the primary menu

if ( function_exists( 'register_nav_menu' ) ) {
	register_nav_menu( 'topmenu', 'Top menu' );
}



// Creating a custom post type for the Portfolio items

function create_portfolio() {
    $args = array(  
        'label' => __('Portfolio items'),  
        'singular_label' => __('Portfolio Item'),  
        'public' => true,  
        'show_ui' => true,  
        'capability_type' => 'post',  
        'hierarchical' => false,  
        'rewrite' => array('slug' => 'portfolio_item'),  
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'comments'),
        'menu_position' => 5,
        'menu_icon' => get_bloginfo(template_directory) . '/images/portfolio_icon.png'
       );  
  
    register_post_type( 'portfolio_item' , $args ); 
}

function admin_init(){
	add_meta_box("portfolio-feature", "Extra settings", "meta_options", "portfolio_item", "side", "low");
}

function meta_options(){
	global $post;
	$custom = get_post_custom($post->ID);
	$home_featured = $custom["home_featured"][0];
?>
	<input type="checkbox" name="home_featured" value="featured" id="home_featured" <?php if ($home_featured == 'yes') echo('checked="checked"'); ?> /> <label for="home_featured">Featured on Home page</label><br/>
<?php
	}

function save_featured(){
	global $post;
	$value = 'no';
	if (isset($_POST["home_featured"])) $value = 'yes';
	update_post_meta($post->ID, "home_featured", $value);
}



// Setting up the comments listing

if ( ! function_exists( 'beatific_comment' ) ) {

	function beatific_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case '' :
		?>
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="image left">
				<?php echo get_avatar( $comment, 50, get_bloginfo(template_directory) . '/images/gravatar.jpg' ); ?>
			</div>
			<div class="commentmeta left">
				<?php
					printf( __( '<span class="author">%s</span>' ), sprintf( '%s', get_comment_author_link() ) );
					printf( __( '%1$s<br/>%2$s'), get_comment_date(),  get_comment_time() ); ?>
				<span class="links"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				<?php edit_comment_link( __( 'Edit' ), '- ' ); ?></span>
			</div>
				
			
			<div class="text">
				<div class="arrow"></div>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p><em><?php _e( 'Your comment is awaiting moderation.' ); ?></em></p>
					<br />
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			
			<div class="clear"></div>
		</div>
	
		<?php
				break;
			case 'pingback'  :
			case 'trackback' :
		?>
		<div class="comment post pingback">
			<p><?php _e( 'Pingback:' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)' ), ' ' ); ?></p>
		<?php
				break;
		endswitch;
	}
}




// Registering the custom Beatific widget for the sidebar

class Beatific_widget extends WP_Widget {
	function Beatific_widget() {
		$widget_ops = array( 'classname' => 'beatific', 'description' => 'Widget with big, selectable icons, header, small header, text and a link to a subpage.' );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'beatific-widget' );
		$this->WP_Widget( 'beatific-widget', 'Beatific widget', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title'] );
		$image = $instance['image'];
		$subtitle = $instance['subtitle'];
		$text = $instance['text'];
		$link = $instance['link'];
		$url = $instance['url'];

		echo $before_widget;
		
		echo '<img src="' . get_bloginfo(template_directory) . '/images/icons/icon_' . $image . '.jpg" alt="Icon" class="header" />';
		echo '<div class="colheader">';
		if ( $title ) echo $before_title . $title . $after_title;
		if ( $subtitle ) echo '<small class="header">' . $subtitle . '</small>';
		echo '</div>';
		if ( $text ) echo '<p>'. $text .'</p>';
		if (( $link ) && ( $url )) echo '<a href="' . $url. '" class="go">' . $link . ' &#187;</a>';
		
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['image'] = $new_instance['image'];
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		$instance['text'] = strip_tags( $new_instance['text'] );
		$instance['link'] = strip_tags( $new_instance['link'] );
		$instance['url'] = strip_tags( $new_instance['url'] );

		return $instance;
	}
	
	function form( $instance ) {
	
		$defaults = array( 'image' => 'users', 'title' => 'Example header', 'subtitle' => 'Small text bellow header', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ut lorem id dui ultricies bibendum id eget metus. In hac habitasse platea dictumst. Ut vel nulla nisl, eget dictum.', 'link' => 'Read more', 'url' => home_url( '/' ));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>">Image:</label>
			<select id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" class="widefat">
				<option <?php if ( 'users' == $instance['image'] ) echo 'selected="selected"'; ?> value="users">User head with address book icon</option>
				<option <?php if ( 'piechart' == $instance['image'] ) echo 'selected="selected"'; ?> value="piechart">Piechart icon</option>
				<option <?php if ( 'mail' == $instance['image'] ) echo 'selected="selected"'; ?> value="mail">Mail icon</option>
				<option <?php if ( 'home' == $instance['image'] ) echo 'selected="selected"'; ?> value="home">House icon</option>
				<option <?php if ( 'bubble' == $instance['image'] ) echo 'selected="selected"'; ?> value="bubble">Speech bubble icon</option>
				<option <?php if ( 'rss' == $instance['image'] ) echo 'selected="selected"'; ?> value="rss">RSS feed icon</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'subtitle' ); ?>">Subtitle:</label>
			<input id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" value="<?php echo $instance['subtitle']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>">Text:</label><br/>
			<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="widefat" cols="30" rows="6"><?php echo $instance['text']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>">'Read More' text:</label>
			<input id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>">'Read More' URL:</label>
			<input id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url']; ?>" class="widefat" />
		</p>
		<?php
	}
}




// Registering the theme settings

$themename = "Beatific";
$shortname = "beatific";

$options = array (
	array("name" => "General settings",
		"desc" => "General settings for the <b>Beatific theme by Zoltan Hosszu</b>.",
		"type" => "title"),
		
	array("name" => "Color scheme",
		"desc" => "Choose your color for the theme.",
		"id" => $shortname . "_color",
		"std" => "blue",
		"options" => array('blue', 'green', 'pink', 'navy'),
		"type" => "select"),
		
	array("name" => "Logo URL",
		"desc" => "For the best result use .PNG file with transparency and maximum 90px size in height. If left empty the blog's name will be used.",
		"id" => $shortname . "_logourl",
		"std" => "",
		"type" => "text"),
		
	array("name" => "Sitemap URL",
		"desc" => "This is the page URL for the 'Sitemap' button above the menu. If you leave this empty, the sitemap button will not be displayed.",
		"id" => $shortname . "_sitemap",
		"std" => "",
		"type" => "text"),
		
	array("name" => "Menu type",
		"desc" => "Select the menu type you would like to use. <b>Images</b> will look nicer but you will only have 5 options (Home, Portfolio, About, Blog, Contact); <b>Text</b> will be easier to change.",
		"id" => $shortname . "_textmenu",
		"std" => "",
		"options" => array('images', 'text'),
		"type" => "select"),
		
	array("name" => "Footer settings",
		"desc" => "Settings for the site's footer.",
		"type" => "title"),
		
	array("name" => "Twitter username",
		"desc" => "This will be shown in the footer. Only the username is required, wihtout the @ or the twitter.com (e.g. <em>zoltanhosszu</em>). If left empty the Twitter widget will be removed from the footer.",
		"id" => $shortname . "_twitter",
		"std" => "",
		"type" => "text"),
		
	array("name" => "Footer form code",
		"desc" => "This should be the Contact Form 7 code for the footer contact form. In order to work, don't include any \" characters in it. The Contact Form 7 plugin gives you a code like [contact-form 2 \"Footer contact form\"], please only use the [contact-form 2] from that code.",
		"id" => $shortname . "_footer_contact",
		"std" => "[contact-form 2]",
		"type" => "text"),
		
	array("name" => "Show Footer on this pages",
		"desc" => "",
		"type" => "tr_start"),
		
	array("name" => "Home page",
		"desc" => "",
		"id" => $shortname . "_home_footer",
		"type" => "checkbox"),
		
	array("name" => "About page",
		"desc" => "",
		"id" => $shortname . "_about_footer",
		"type" => "checkbox"),
		
	array("name" => "Blog page",
		"desc" => "",
		"id" => $shortname . "_blog_footer",
		"type" => "checkbox"),
		
	array("name" => "Contact page",
		"desc" => "",
		"id" => $shortname . "_contact_footer",
		"type" => "checkbox"),
		
	array("name" => "Portfolio page",
		"desc" => "",
		"id" => $shortname . "_portfolio_footer",
		"type" => "checkbox"),
		
	array("name" => "Full width pages",
		"desc" => "",
		"id" => $shortname . "_fullwidth_footer",
		"type" => "checkbox"),
		
	array("name" => "end",
		"desc" => "",
		"type" => "tr_end"),
		
	array("name" => "Home page settings",
		"desc" => "Settings for the page with the 'Home template' applied.",
		"type" => "title"),
		
	array("name" => "Learn more button URL",
		"desc" => "The URL of the 'Learn more about it!' button.",
		"id" => $shortname . "_learnmore",
		"std" => "",
		"type" => "text"),
		
	array("name" => "Default portfolio item URL",
		"desc" => "The URL of an image for the Featured item section if there are no portfolio items selected to be featured.",
		"id" => $shortname . "_portfolioimage",
		"std" => "",
		"type" => "text"),
		
	array("name" => "Right side content ID",
		"desc" => "This is a small content next to the right of the Home page content. By default it was a list. Do the following to find this:<br/>- Create a new page under Pages.<br/> - Press Edit.<br/>- At the middle of the URL of the page you will find this: .../post.php?post=[NUMBER]<br/>- Copy the [NUMBER] and paste it here.",
		"id" => $shortname . "_homelist",
		"std" => "",
		"type" => "text"),
);

function beatific_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {

        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) {
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
                    } else {
                    	delete_option( $value['id'] ); 
                    }
				}

                header("Location: options-general.php?page=functions.php&saved=true");
                die;

        }
    }

    add_submenu_page('options-general.php', "Theme settings", "Theme settings", 'administrator', basename(__FILE__), 'beatific_admin');

}

function beatific_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>'; ?>

	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br/></div><h2><?php echo $themename; ?> theme settings</h2>
		
		<form method="post" action="options-general.php?page=functions.php">
		
			<table class="form-table">
			<?php foreach ($options as $value) {
				switch ( $value['type'] ) {
				
					case 'text': ?>
					
					<tr>
					    <th scope="row" valign="top"><b><?=$value['name'];?></b></th>
					    <td><input class="widefat" name="<?=$value['id'];?>" id="<?=$value['id'];?>" type="text" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /><br/><small><?=$value['desc'];?></small></td>
					</tr>
					
					<?php break;
					
					case 'select': ?>
					
					<tr>
					    <th scope="row" valign="top"><b><?=$value['name'];?></b></th>
					    <td><select class="widefat" name="<?=$value['id'];?>" id="<?=$value['id'];?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?> value="<?=$option?>"><?=ucfirst($option);?><? if ($option == 'blue') echo ' (default)';?></option><?php } ?></select><br/><small><?=$value['desc'];?></small></td>
					</tr>
					
					<?php break;
					
					case 'tr_start': ?>
					
					<tr>
					    <th scope="row" valign="top"><b><?=$value['name'];?></b></th>
					    <td>
					
					<?php break;
					
					case 'tr_start': ?>
					
					</tr>
					
					<?php break;
					
					case 'checkbox': ?>
					
					<input type="checkbox" name="<?=$value['id'];?>" id="<?=$value['id'];?>" <?php if ( get_settings( $value['id'] ) == "on") echo 'checked="checked"';  ?>> <label for="<?=$value['id'];?>"><?=$value['name'];?></label><br/>
					
					<?php break;
					
					case 'textarea': ?>
					
					<tr>
					    <th scope="row" valign="top"><b><?=$value['name'];?></b></th>
					    <td><textarea cols="30" rows="6" class="widefat" name="<?=$value['id'];?>" id="<?=$value['id'];?>"><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea><br/><small><?=$value['desc'];?></small></td>
					</tr>
					
					<?php break;
					
					case 'title': ?>
					
					<tr>
					    <th colspan="2" scope="row" valign="top"><h3><?php echo $value['name']; ?></h3><p><?=$value['desc'];?></p></th>
					</tr>
					
					<?php break;
									
				}
			}
			?>
			</table>
			<input name="save" type="submit" value="Save settings" class="button-primary" />
			<input type="hidden" name="action" value="save" /><br/><br/>
		</form>
	</div>
<?php
}




// Adding the dynamic sidebar areas

if ( function_exists('register_sidebar') ) {
 
	function beatific_widgets_init() {
		register_sidebar( array(
			'name' => __( 'Primary Widget Area' ),
			'id' => 'primary-widget-area',
			'description' => __( 'The primary widget area' ),
			'before_widget' => '<div class="column" id="%1$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Blog Widget Area' ),
			'id' => 'blog-widget-area',
			'description' => __( 'The widgets shown on the "Blog" page.' ),
			'before_widget' => '<div class="column widget" id="%1$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		) );
		
		register_widget('Beatific_widget');
	}
	
	add_action( 'widgets_init', 'beatific_widgets_init' );

}





// DEFAULT WIDGETS FROM TWENTYTEN THEME

function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading &#187;') . '</a>';
}

function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;<br/><br/>' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
	function twentyten_posted_on() {
		printf( __( '%2$s </span> <span class="author">%3$s' ),
			'meta-prep meta-prep-author',
			sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
				get_permalink(),
				esc_attr( get_the_time() ),
				get_the_date()
			),
			sprintf( '<a class="url fn n" href="%1$s" title="%2$s">%3$s</a>',
				get_author_posts_url( get_the_author_meta( 'ID' ) ),
				sprintf( esc_attr__( 'View all posts by %s'), get_the_author() ),
				get_the_author()
			)
		);
	}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
	function twentyten_posted_in() {
		// Retrieves tag list of current post, separated by commas.
		$tag_list = get_the_tag_list( '', ', ' );
		if ( $tag_list ) {
			$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.' );
		} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
			$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.' );
		} else {
			$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.' );
		}
		// Prints the string, replacing the placeholders.
		printf(
			$posted_in,
			get_the_category_list( ', ' ),
			$tag_list,
			get_permalink(),
			the_title_attribute( 'echo=0' )
		);
	}
endif;
