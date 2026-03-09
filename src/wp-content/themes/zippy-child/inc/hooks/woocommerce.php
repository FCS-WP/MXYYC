<?php

/**
 * Add 'All' option to product category widget
 */
add_filter('woocommerce_product_categories_widget_args', function($args) {
    $args['show_option_all'] = 'All';
    return $args;
});

/**
 * Add Shop page to breadcrumb on product category pages
 */
add_filter('woocommerce_get_breadcrumb', function($crumbs, $breadcrumb) {
    if (is_product_category()) {

        $shop_page_id = wc_get_page_id('shop');

        if ($shop_page_id) {
            $shop = array(
                get_the_title($shop_page_id),
                get_permalink($shop_page_id)
            );

            // insert after Home
            array_splice($crumbs, 0, 0, array($shop));
        }
    }

    return $crumbs;
}, 10, 2);

/**
 * Add product attrubutes to product summary area
 */
add_action('woocommerce_single_product_summary', function() {
    global $product;

    echo '<h2 class="product-attributes-title">Aditional Information</h2>';
    wc_display_product_attributes($product);
}, 100);

/**
 * Move add to cart button to after the product price
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 15);

/**
 * Move add to cart button to after the product price
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 105);
