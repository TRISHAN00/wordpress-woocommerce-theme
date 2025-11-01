/**
 * ============================================================
 * AJAX Remove from Cart + Live Subtotal Update (No Page Reload)
 * ============================================================
 */

(function ($) {
  "use strict";

  // ✅ Fix WooCommerce blockUI dependency issue (if BlockUI is missing)
  if (typeof $.fn.block !== "function") {
    $.fn.block = function () { return this; };
  }
  if (typeof $.fn.unblock !== "function") {
    $.fn.unblock = function () { return this; };
  }

  /**
   * 🔥 Function to refresh subtotal instantly via AJAX
   */
  function updateCartSubtotal() {
    $.ajax({
      url: wc_add_to_cart_params.ajax_url, // WooCommerce's global AJAX URL
      type: 'POST',
      data: { action: 'get_cart_subtotal' },
      success: function (response) {
        if (response.success && response.data.subtotal_html) {
          // Update subtotal element
          $('.cart-subtotal .amount').html(response.data.subtotal_html);
        }
      }
    });
  }

  /**
   * 🧩 Handle Remove from Cart (Custom AJAX)
   */
  $(document).on('click', '.remove-cart-item', function (e) {
    e.preventDefault();

    let $this = $(this);
    let cartItemKey = $this.data('cart-item-key');

    $.ajax({
      url: wc_add_to_cart_params.ajax_url,
      type: 'POST',
      data: {
        action: 'remove_cart_item',
        cart_item_key: cartItemKey,
        nonce: ajax_add_to_cart.nonce
      },
      beforeSend: function () {
        $this.addClass('loading');
      },
      success: function (response) {
        $this.removeClass('loading');

        if (response.success) {
          // Remove the item visually
          $this.closest('.cart-item').fadeOut(300, function () {
            $(this).remove();
            updateCartSubtotal(); // ✅ Refresh subtotal instantly
          });

          // Optional: update cart count badge
          if (response.data && response.data.cart_count !== undefined) {
            $('.cart-count').text(response.data.cart_count);
          }
        }
      }
    });
  });

})(jQuery);
