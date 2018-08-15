<?php

/**
 * The Template for displaying all single posts.
 *
 * @package Play School
 */

get_header(); ?>

<div class="container">
     <div class="page_content">
        <section class="site-main">            
        <div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
        <?php
            //get current url
        $slug = pods_v('last', 'url');
            //get pod name
        $pod_name = pods_v(0, 'url');
            //get pods object for current item
        $pods = pods($pod_name, $slug);
        ?>
            <article>
                <?php
                    //Output template of the same name as Pod, if such a template exists.
                $temp = $pods->template($pod_name);
                if (isset($temp)) {
                    echo $temp;
                }
                ?>
            </article><!-- #post -->

		</div><!-- #content -->
	</div><!-- #primary -->  
         </section>       
        <?php get_sidebar(); ?>
       
        <div class="clear"></div>
    </div><!-- page_content -->
</div><!-- container -->	
<?php get_footer(); ?>