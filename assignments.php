<?php

/**
 * 
 * Template Name: Assignments
 * 
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Play School
 */

get_header(); ?>
//set find parameters
$params = array( 'limit' => 3 );
//get pods object
$pods = pods( 'assignment', $params );

<div class="container">
      <div class="page_content">
    		 <section class="site-main">               
                    <?php if ($pods->total() > 0) {
                        while ($pods->fetch()) {
                            $title = $pods->display('name');
                            $desc = $pods->display('description');
                            $permalink = site_url('assignment/' . $pods->field('permalink'));
                            ?>
                                   <article> <h1 class="entry-title">
                                        <a href="<?php echo $permalink; ?>" rel="assignment"><?php echo $title; ?></a>
                                    </h1>
                                       
                                <div class="entry-content">
                                    <?php echo $desc; ?>                                      
                                            
                                </div><!-- entry-content -->
                    </article>
                    <?php 
                }
                } ?>
                              
                //do the pagination
                echo $pods->pagination( array( 'type' => 'advanced' ) );
            </section><!-- section-->
   
     <?php get_sidebar(); ?>      
    <div class="clear"></div>
    </div><!-- .page_content --> 
 </div><!-- .container --> 
<?php get_footer(); ?>