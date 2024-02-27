<?php
wp_nav_menu([
    'container' => 'div',
    'container_class' => 'nav-scroller py-1 mb-3 border-bottom',
    'theme_location' => 'primary',
    'depth' => 1,
    'link_class' => 'nav-item nav-link link-body-emphasis',
    'menu_class' => 'nav nav-underline justify-content-between',
    'fallback_cb' => 'bootstrap_fallback_cb'
]);
?>