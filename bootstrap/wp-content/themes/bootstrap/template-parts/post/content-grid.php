<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
    <div class="col p-4 d-flex flex-column position-static">
    <strong class="d-inline-block mb-2 text-primary-emphasis"><?php echo wp_strip_all_tags(get_the_category_list(', ')); ?></strong>
    <h3 class="mb-0"><?php echo wp_trim_words(get_the_title(), 3, '..'); ?></h3>
    <div class="mb-1 text-body-secondary">Nov 12</div>
    <p class="card-text mb-auto"><?php echo wp_trim_words(get_the_excerpt(), 14); ?></p>
    <a href="<?php the_permalink(); ?>" class="icon-link gap-1 icon-link-hover stretched-link">
        Continue reading
        <svg class="bi"><use xlink:href="#chevron-right"/></svg>
    </a>
    </div>
    <?php if(has_post_thumbnail()): ?>
    <div class="col-auto d-none d-lg-block">
        <?php the_post_thumbnail('medium', ['class' => 'h-100 object-fit-cover']) ?>
    </div>
    <?php endif; ?>
</div>