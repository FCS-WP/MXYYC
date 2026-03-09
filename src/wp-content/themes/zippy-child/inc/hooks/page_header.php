<?php

// [page_header]
function yyc_flatsome_page_header_shortcode($atts) {
  ob_start();
  ?>
  <div id="page-header-yyc" class="page-header-wrapper has-block">
    <div class="page-title light simple-title">
      <div class="page-title-inner container align-center text-center flex-row-col medium-flex-wrap">
        <div class="title-wrapper is-xlarge flex-col">
          <h1 class="entry-title mb-0">
            <?php if (is_shop() || is_product()) : ?>
              <?php echo woocommerce_page_title(); ?>
            <?php elseif (is_product_category()) : ?>
              <?php
                $term = get_queried_object();
                echo $term->name;
              ?>
            <?php else: ?>
              <?php the_title(); ?>
            <?php endif; ?>
          </h1>
        </div>
      </div>
      <style>
        #page-header-yyc .page-title-inner {
          min-height: 200px;
        }
        #page-header-yyc .title-overlay {
          background-color: rgba(255, 255, 255, 0);
        }
        #page-header-yyc {
          background-color: rgba(255, 255, 255, 0);
        }
      </style>
    </div>
  </div>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

add_shortcode("yyc_page_header", "yyc_flatsome_page_header_shortcode");
