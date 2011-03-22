<?php

/*

 * @package WordPress

 * @subpackage Beatific by Zoltan Hosszu (http://www.zoltanhosszu.com/)

 * @since Beatific 1.0

 */

?>

 

 <!-- BASE-HOME.PHP -->





<div id="wrapper">
	<div id="header">
    	<div id="logo">
        	<img src="<?=get_bloginfo(template_directory); ?>/images/logo.jpg" alt="Service Vines"/><p>A market for outdoor services</p>
        </div>
        <div id="headerText">
        	<p>Companines want your business.<br/>Let them compete for it</p>
        </div>
    </div>
    <div id="left">
    	<div class="step">
        	<img src="<?=get_bloginfo(template_directory); ?>/images/1.jpg" alt="1 - Post for free your job and what to spend" />
        </div>
        <div class="step">
        	<img src="<?=get_bloginfo(template_directory); ?>/images/2.jpg" alt="2 - Receive Bids back from providers" />
        </div>
<div class="step">
        	<img src="<?=get_bloginfo(template_directory); ?>/images/3.jpg" alt="3 - Pick using ratings and work descriptions" />
        </div>
        <div id="signupForm">
        <p class="header">Sign up for more info</p>
        <? echo do_shortcode(get_settings('beatific_footer_contact')); ?>
        </div>
    </div>
    <div id="right">
    	<div id="postJob">
        	<p class="header">
            	Post a job now and get bids
            </p>
            <div id="jobTypes">
            	<p class="header">
                	First, select the type of job:
                </p>
                <div id="types">
                	<div class="jobType">
                    	<a href="./app/jobs/create/type:1/"><img src="<?=get_bloginfo(template_directory); ?>/images/tree_trimming_removal_small.jpg" alt="Tree Trimming" />
                        <p>Tree Trimming/Removal</p></a>
                    </div>
<div class="jobType">
                    	<a href="./app/jobs/create/type:2/"><img src="<?=get_bloginfo(template_directory); ?>/images/landscaping_small.jpg" alt="Landscaping" />
                        <p>Landscaping</p></a>
                    </div>
                    <div class="jobType">
                    	<a href="./app/jobs/create/type:4/"><img src="<?=get_bloginfo(template_directory); ?>/images/snow_removal_small.jpg" alt="Snow Removal" />
                        <p>Snow Removal</p></a>
                    </div>
                    <div class="jobType">
                    	<a href="./app/jobs/create/type:3/"><img src="<?=get_bloginfo(template_directory); ?>/images/lawn_main_small.jpg" alt="Lawn Maintenance" />
                        <p>Lawn Maintenance</p></a>
                    </div>
                    <div class="jobType">
                    	<a href="./app/jobs/create/type:5/"><img src="<?=get_bloginfo(template_directory); ?>/images/fencing_small.jpg" alt="Fencing" />
                        <p>Fencing</p></a>
                    </div>
                    <div class="jobType">
                    	<a href="./app/jobs/create/type:6/"><img src="<?=get_bloginfo(template_directory); ?>/images/pool_maintenance_small.jpg" alt="Pool Manintenance" />
                        <p>Pool Manintenance</p></a>
                    </div>
                </div>
            </div>
        </div>
        <div id="providers">
        	<img src="<?=get_bloginfo(template_directory); ?>/images/providers.jpg" alt="Providers - create your provile (coming soon)" />
        </div>
    </div>
    <div id="footer">
    	<p>
    	<a href="http://www.servicevines.com/">Home</a>&nbsp;&nbsp;| &nbsp;&nbsp;
    	<a href="http://www.servicevines.com/?page_id=2">About Us</a>&nbsp;&nbsp;| &nbsp;&nbsp;
    	<a href="http://www.servicevines.com/?page_id=25">Our Mission</a>&nbsp;&nbsp;| &nbsp;&nbsp;
    	<a href="http://www.servicevines.com/?page_id=34">Blog</a>      
        </p>
        <p>(c) 2011 Service Vines Inc.</p>
    </div>