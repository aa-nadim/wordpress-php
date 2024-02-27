<?php
global $bootstrap_banner_posts, $bootstrap_grid_posts;

if( !empty($bootstrap_banner_posts) ) :
  // The Query.
  $the_query = new WP_Query( [
    'post_type' => 'post',
    'ignore_sticky_posts' => true,
    'post__in' => $bootstrap_banner_posts
  ] );

  // The Loop.
  if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {       
        $the_query->the_post();
        get_template_part('template-parts/post/content', 'banner');      
    }	
  }
  wp_reset_postdata();
endif;

if( !empty($bootstrap_grid_posts) ) :
// The grid content Loop.

  // The Query.
  $the_query = new WP_Query( [
    'post_type' => 'post',
    'ignore_sticky_posts' => true,
    'post__in' => $bootstrap_grid_posts
  ] );
  if ( $the_query->have_posts() ) {
    ?>
    <div class="row mb-2">
      <?php
      while ( $the_query->have_posts() ) {
          $the_query->the_post();	
          ?>
          <div class="col-md-6">
            <?php get_template_part('template-parts/post/content', 'grid');   ?>            
          </div>
        <?php  
      }	
      ?>
    </div>
    <?php
  }
  wp_reset_postdata();
endif;