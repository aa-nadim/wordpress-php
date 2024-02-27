<?php get_header(); ?>

<main class="container">  
  <div class="row g-5 mt-5">
    <div class="col-md-8">
       
      <?php
      if ( have_posts() ) {
       
        // Load posts loop.
        while ( have_posts() ) {
          the_post();  
          get_template_part( 'template-parts/post/content' );
        }

        // Previous/next page navigation.
        get_template_part('template-parts/post/posts-navigation');

      } else {

        // If no content, include the "No posts found" template.
        get_template_part( 'template-parts/content-none' );

      }
      ?>      
    </div>

    <div class="col-md-4">
      <?php get_sidebar(); ?>
    </div>
  </div>

</main>

<?php get_footer(); ?>
