<?php

/**
 * Get the thumbnail ID of the top-level parent product category
 * for the current single product.
 */
function get_top_level_product_cat_thumbnail_id() {
  // Ensure we're on a single product page
  if ( ! is_singular( 'product' ) ) {
    return false;
  }

  // Get the current product ID reliably
  $product_id = get_the_ID();

  // Get the WC_Product object
  $product = wc_get_product( $product_id );

  if ( ! $product || ! $product->exists() ) {
    return false;
  }

  // Get all product categories assigned to this product
  $terms = get_the_terms( $product_id, 'product_cat' );

  if ( ! $terms || is_wp_error( $terms ) ) {
    return false;
  }

  // Collect unique top-level parent term IDs
  $top_level_parents = array();

  foreach ( $terms as $term ) {
    // Get ancestors (returns array from child to top-parent)
    $ancestors = get_ancestors( $term->term_id, 'product_cat', 'taxonomy' );

    if ( ! empty( $ancestors ) ) {
      // The last item in the ancestors array is the top-most parent
      $top_parent_id = end( $ancestors );
    } else {
      // This term is already top-level
      $top_parent_id = $term->term_id;
    }

    $top_level_parents[ $top_parent_id ] = true;
  }

  if ( empty( $top_level_parents ) ) {
    return false;
  }

  // Pick the first top-level parent found
  $top_parent_term_id = key( $top_level_parents );

  // Get the thumbnail ID stored in term meta
  $thumbnail_id = get_term_meta( $top_parent_term_id, 'thumbnail_id', true );

  return $thumbnail_id ? intval( $thumbnail_id ) : false;
}

/**
 * Get the NAME of the top-level parent product category
 * for the current single product.
 *
 * @return string|false Category name, or false if none found.
 */
function get_top_level_product_cat_title() {
  // Ensure we're on a single product page
  if ( ! is_singular( 'product' ) ) {
    return 'Shop';
  }

  $product_id = get_the_ID();
  $terms = get_the_terms( $product_id, 'product_cat' );

  if ( ! $terms || is_wp_error( $terms ) ) {
    return 'Shop';
  }

  // Collect unique top-level parent term IDs
  $top_level_parents = array();

  foreach ( $terms as $term ) {
    $ancestors = get_ancestors( $term->term_id, 'product_cat', 'taxonomy' );

    if ( ! empty( $ancestors ) ) {
      $top_parent_id = end( $ancestors );
    } else {
      $top_parent_id = $term->term_id;
    }

    $top_level_parents[ $top_parent_id ] = true;
  }

  if ( empty( $top_level_parents ) ) {
    return 'Shop';
  }

  // Pick the first top-level parent
  $top_parent_term_id = key( $top_level_parents );

  // Get the term object to access the name
  $term = get_term( $top_parent_term_id, 'product_cat' );

  if ( ! $term || is_wp_error( $term ) ) {
    return 'Shop';
  }

  if ($term->name == 'Uncategorized') {
    return 'Shop';
  }

  return $term->name;
}


/**
 * Display banner image
 */
function display_banner() {
  if (is_product_category()) {
    $term = get_queried_object();
    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);

    if ($thumbnail_id) {
      echo wp_get_attachment_image($thumbnail_id, 'large', false, ['class' => 'banner-image']);
    }
  // } else if (is_product()) {
  //   $thumbnail_id = get_top_level_product_cat_thumbnail_id();

  //   if ( $thumbnail_id ) {
  //     echo wp_get_attachment_image( $thumbnail_id, 'large', false, ['class' => 'banner-image'] );
  //   }
  } else if (is_product() || !is_home() && !is_single()) {
    $id = get_the_id();

    if (is_shop() || is_product()) {
      $id = wc_get_page_id('shop');
    }

    if (has_post_thumbnail($id)) {
      echo get_the_post_thumbnail($id, 'large', ['class' => 'banner-image']);
    }
  }
}

/**
 * Display title
 */
function display_title() {
  if (is_shop()) {
    echo woocommerce_page_title();
  } else if (is_home()) {
    echo 'Blog';
  } else if (is_product_category()) {
    $term = get_queried_object();
    echo $term->name;
  } else if (is_product()) {
    echo 'Shop';
  //   echo get_top_level_product_cat_title();
  } else {
    the_title();
  }
}

// [page_header]
function yyc_flatsome_page_header_shortcode($atts) {
  ob_start();
  ?>
  <div id="page-header-yyc" class="page-header-wrapper has-block container">
    <div class="page-title light simple-title">
      <div class="page-title-inner container align-center text-center flex-row-col medium-flex-wrap">
        <?php display_banner(); ?>
        <div class="title-wrapper is-xlarge flex-col">
          <h1 class="entry-title mb-0">
            <?php display_title(); ?>
          </h1>
        </div>
      </div>
    </div>
  </div>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

add_shortcode("yyc_page_header", "yyc_flatsome_page_header_shortcode");

add_action('flatsome_after_header', function() {
  echo do_shortcode('[yyc_page_header]');
}, 10);
