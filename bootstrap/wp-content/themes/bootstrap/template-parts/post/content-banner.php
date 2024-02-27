<div class="p-4 p-md-5 mb-4 rounded text-body-emphasis bg-body-secondary position-relative overflow-hidden">
    <div class="col-lg-6 px-0 position-relative z-1">
        <h1 class="display-4 fst-italic"><?php the_title(); ?></h1>
        <p class="lead my-3"><?php echo get_the_excerpt(); ?></p>
        <p class="lead mb-0"><a href="<?php the_permalink(); ?>" class="text-body-emphasis fw-bold">Continue reading...</a></p>
    </div>
    <?php if(has_post_thumbnail()): ?>
    <div class="position-absolute w-100 h-100 start-0 top-0 z-0 bg-body-secondary">
        <?php the_post_thumbnail('full', ['class' => 'w-100 h-100 object-fit-cover opacity-25']) ?>
    </div>
    <?php endif; ?>
</div>