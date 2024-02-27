<article class="blog-post">
    <?php the_title('<h2 class="display-5 link-body-emphasis mb-1">', '</h2>'); ?>
    <p class="blog-post-meta"><?php  echo get_the_date();  ?> by <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"> <?php echo get_the_author(); ?> </a></p>    
    <?php the_content(); ?>   
</article>