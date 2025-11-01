<?php
defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>

<!-- Account Dashboard Start -->
<div class="account-dashboard-area py-5">
  <div class="container">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 account-title "><?php esc_html_e( 'My Account', 'woocommerce' ); ?></h5>
        <div class="list-group account-sidebar">
          <?php
          // WooCommerce default account menu
          foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
            $url   = wc_get_account_endpoint_url( $endpoint );
            $class = wc_get_account_menu_item_classes( $endpoint );
            echo '<a href="' . esc_url( $url ) . '" class="list-group-item list-group-item-action ' . esc_attr( $class ) . '">' . esc_html( $label ) . '</a>';
          }
          ?>
        </div>
      </div>

      <!-- Content -->
      <div class="col-md-9">
        <div class="tab-content p-4 bg-white border rounded shadow-sm">
          <?php
          /**
           * My Account content.
           * Dynamically loads WooCommerce endpoints (dashboard, orders, downloads, etc.)
           */
          do_action( 'woocommerce_account_content' );
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
