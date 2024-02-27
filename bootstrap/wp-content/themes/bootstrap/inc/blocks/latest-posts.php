
<?php
remove_action( 'init', 'register_block_core_latest_posts' );
/**
 * Renders the `core/latest-posts` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns the post content with latest posts added.
 */
function bootstrap_render_block_core_latest_posts( $attributes ) {
	global $post, $block_core_latest_posts_excerpt_length;

	$args = array(
		'posts_per_page'      => $attributes['postsToShow'],
		'post_status'         => 'publish',
		'order'               => $attributes['order'],
		'orderby'             => $attributes['orderBy'],
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$block_core_latest_posts_excerpt_length = $attributes['excerptLength'];
	add_filter( 'excerpt_length', 'block_core_latest_posts_get_excerpt_length', 20 );

	if ( ! empty( $attributes['categories'] ) ) {
		$args['category__in'] = array_column( $attributes['categories'], 'id' );
	}
	if ( isset( $attributes['selectedAuthor'] ) ) {
		$args['author'] = $attributes['selectedAuthor'];
	}

	$query        = new WP_Query();
	$recent_posts = $query->query( $args );

	if ( isset( $attributes['displayFeaturedImage'] ) && $attributes['displayFeaturedImage'] ) {
		update_post_thumbnail_cache( $query );
	}

	$list_items_markup = '';

	foreach ( $recent_posts as $post ) {
		$post_link = esc_url( get_permalink( $post ) );
		$title     = get_the_title( $post );

		if ( ! $title ) {
			$title = __( '(no title)' );
		}

		$list_items_markup .= '<li>';
		$list_items_markup .= '<a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="'.$post_link.'">';

		if ( $attributes['displayFeaturedImage'] && has_post_thumbnail( $post ) ) {
			$image_style = '';
			if ( isset( $attributes['featuredImageSizeWidth'] ) ) {
				$image_style .= sprintf( 'max-width:%spx;', $attributes['featuredImageSizeWidth'] );
			}
			if ( isset( $attributes['featuredImageSizeHeight'] ) ) {
				$image_style .= sprintf( 'max-height:%spx;', $attributes['featuredImageSizeHeight'] );
			}

			$image_classes = 'wp-block-latest-posts__featured-image';
			if ( isset( $attributes['featuredImageAlign'] ) ) {
				$image_classes .= ' align' . $attributes['featuredImageAlign'];
			}

			$featured_image = get_the_post_thumbnail(
				$post,
				$attributes['featuredImageSizeSlug'], 
				array(
					'style' => esc_attr( $image_style ),
				)
			);
			if ( $attributes['addLinkToFeaturedImage'] ) {
				$featured_image = sprintf(
					'<a href="%1$s" aria-label="%2$s">%3$s</a>',
					esc_url( $post_link ),
					esc_attr( $title ),
					$featured_image
				);
			}
			$list_items_markup .= sprintf(
				'<div class="%1$s">%2$s</div>',
				esc_attr( $image_classes ),
				$featured_image
			);

			$list_items_markup .= '<div class="col-lg-8">';
		}else{
            $list_items_markup .= '<div class="col-lg-12">';
        }

		$list_items_markup .= sprintf(
			'<h6 class="wp-block-latest-posts__post-title mb-0">%1$s</h6>',
			$title
		);

		if ( isset( $attributes['displayAuthor'] ) && $attributes['displayAuthor'] ) {
			$author_display_name = get_the_author_meta( 'display_name', $post->post_author );

			/* translators: byline. %s: current author. */
			$byline = sprintf( __( 'by %s' ), $author_display_name );

			if ( ! empty( $author_display_name ) ) {
				$list_items_markup .= sprintf(
					'<div class="wp-block-latest-posts__post-author">%1$s</div>',
					$byline
				);
			}
		}

		if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
			$list_items_markup .= sprintf(
				'<time datetime="%1$s" class="wp-block-latest-posts__post-date text-body-secondary">%2$s</time>',
				esc_attr( get_the_date( 'c', $post ) ),
				get_the_date( '', $post )
			);
		}

		if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent']
			&& isset( $attributes['displayPostContentRadio'] ) && 'excerpt' === $attributes['displayPostContentRadio'] ) {

			$trimmed_excerpt = get_the_excerpt( $post );

			/*
			 * Adds a "Read more" link with screen reader text.
			 * [&hellip;] is the default excerpt ending from wp_trim_excerpt() in Core.
			 */
			if ( str_ends_with( $trimmed_excerpt, ' [&hellip;]' ) ) {
				$excerpt_length = (int) apply_filters( 'excerpt_length', $block_core_latest_posts_excerpt_length );
				if ( $excerpt_length <= $block_core_latest_posts_excerpt_length ) {
					$trimmed_excerpt  = substr( $trimmed_excerpt, 0, -11 );
					$trimmed_excerpt .= sprintf(
						/* translators: 1: A URL to a post, 2: Hidden accessibility text: Post title */
						__( '… <a href="%1$s" rel="noopener noreferrer">Read more<span class="screen-reader-text">: %2$s</span></a>' ),
						esc_url( $post_link ),
						esc_html( $title )
					);
				}
			}

			if ( post_password_required( $post ) ) {
				$trimmed_excerpt = __( 'This content is password protected.' );
			}

			$list_items_markup .= sprintf(
				'<div class="wp-block-latest-posts__post-excerpt">%1$s</div>',
				$trimmed_excerpt
			);
		}

		if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent']
			&& isset( $attributes['displayPostContentRadio'] ) && 'full_post' === $attributes['displayPostContentRadio'] ) {

			$post_content = html_entity_decode( $post->post_content, ENT_QUOTES, get_option( 'blog_charset' ) );

			if ( post_password_required( $post ) ) {
				$post_content = __( 'This content is password protected.' );
			}

			$list_items_markup .= sprintf(
				'<div class="wp-block-latest-posts__post-full-content">%1$s</div>',
				wp_kses_post( $post_content )
			);
		}
		

		

		$list_items_markup .= '</div></a>';
		$list_items_markup .= "</li>\n";
	}

	remove_filter( 'excerpt_length', 'block_core_latest_posts_get_excerpt_length', 20 );

	$classes = array( 'wp-block-latest-posts__list', 'list-unstyled' );
	if ( isset( $attributes['postLayout'] ) && 'grid' === $attributes['postLayout'] ) {
		$classes[] = 'is-grid';
	}
	if ( isset( $attributes['columns'] ) && 'grid' === $attributes['postLayout'] ) {
		$classes[] = 'columns-' . $attributes['columns'];
	}
	if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
		$classes[] = 'has-dates';
	}
	if ( isset( $attributes['displayAuthor'] ) && $attributes['displayAuthor'] ) {
		$classes[] = 'has-author';
	}
	if ( isset( $attributes['style']['elements']['link']['color']['text'] ) ) {
		$classes[] = 'has-link-color';
	}

	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => implode( ' ', $classes ) ) );

	return sprintf(
		'<ul %1$s>%2$s</ul>',
		$wrapper_attributes,
		$list_items_markup
	);
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function bootstrap_register_block_core_latest_posts() {
	register_block_type_from_metadata(
		ABSPATH .'wp-includes/blocks/latest-posts',
		array(
			'render_callback' => 'bootstrap_render_block_core_latest_posts',
		)
	);
}
add_action( 'init', 'bootstrap_register_block_core_latest_posts' );