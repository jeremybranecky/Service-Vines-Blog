<?php

/*

 * @package WordPress

 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)

 * @since Beatific 1.0

 */

?>

 

 <!-- BASE-HOME.PHP -->



<?php get_template_part( 'base', 'body' ); ?>



<div class="main">

	<div id="postJob">

	<a href="/app/"><img src="./wp-content/themes/beatific/images/post_a_job_btn.jpg" alt="Post a job now!" border="0"/></a>

</div>

	

	<div class="scroller">

		

			<div class="contact">	

<h3>Sign up for newsletter</h3>

				<? echo do_shortcode(get_settings('beatific_footer_contact')); ?>

			</div>

<br/>

<a href="<? if (get_settings('beatific_learnmore') != '') echo get_settings('beatific_learnmore'); else echo('about/'); ?>" id="learnmore">Learn more about it!</a>

	</div>



</div>

<div id="jobTypes">
   		
   		<div class="jobType">
        	<p> <a href="/app/jobs/create/type:1/"><img alt="Tree trimming/removal" src="/app/webroot/img/jobs/tree_trimming_removal.gif"></a></p>
            <p>Tree Trimming/Removal</p>
        </div>
        
        <div class="jobType">
        	<p> <a href="/app/jobs/create/type:2/"><img alt="Landscaping" src="/app/webroot/img/jobs/landscaping.gif"></a></p>
            <p>Landscaping</p>
        </div>
        <div class="jobType">
        	<p><a href="/app/jobs/create/type:3/"><img alt="Lawn Maintenance" src="/app/webroot/img/jobs/lawn_maintenance.gif"></a></p>
            <p>Lawn Maintenance</p>
        </div>
        <div class="jobType">
        	<p><a href="/app/jobs/create/type:4/"><img alt="Snow Removal" src="/app/webroot/img/jobs/snow_removal.gif"></a></p>
            <p>Snow Removal</p>
        </div>
        <div class="jobType">
        	<p><a href="/app/jobs/create/type:5/"><img alt="Fencing" src="/app/webroot/img/jobs/fencing.gif"></a></p>
            <p>Fencing</p>
        </div>
        <div class="jobType">
        	<p><a href="/app/jobs/create/type:6/"><img alt="Pool Maintenance" src="/app/webroot/img/jobs/pool_maintenance.gif"></a></p>
            <p>Pool Maintenance</p>
        </div>
   </div>


<?php get_sidebar(); ?>



<?php query_posts($query_string); while ( have_posts() ) : the_post(); ?>



<div class="twocolumn">

	<div class="content">

		<div class="leftcolumn">

			<h2><?php //the_title(); ?></h2>

			<div class="entry-content">

				<?php the_content(); ?>

			</div>

		</div>

<? endwhile; ?>

	<? if (get_settings('beatific_homelist') != '') : ?>

		<div class="rightcolumn">

		

			<?php

			$postid= get_settings('beatific_homelist');

			$listpost = get_post($postid);

			$title = $listpost->post_title;

			$content = $listpost->post_content;

			

			echo('<h2>' . $title . '</h2>');

			echo('<p>' . $content . '</p>');

			?>

			

		</div>

	<? endif; ?>

	</div>

</div>



<div class="clear"></div>