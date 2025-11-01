/**
 * ============================================================================
 * AJAX Add to Cart System - Complete JavaScript Handler
 * Handles: Add to cart, Remove from cart, Update quantity, Cart sidebar
 * Everything works without page reload
 * ============================================================================
 */

(function ($) {
  "use strict";

  // Define dummy block/unblock if missing
  if (typeof $.fn.block !== "function") {
    $.fn.block = function() {
      return this;
    };
  }
  if (typeof $.fn.unblock !== "function") {
    $.fn.unblock = function() {
      return this;
    };
  }

  // Optional: Fix WooCommerce body event calls (defensive)
  if (typeof $.blockUI === "undefined") {
    $.blockUI = function() {};
    $.unblockUI = function() {};
  }

  // ============================================================================
  // CART SIDEBAR FUNCTIONALITY
  // ============================================================================

  /**
   * Open Cart Sidebar
   */
  function openCartSidebar() {
    $("#mini-cart-sidebar").addClass("active");
    $(".overlay").addClass("active");
    $("body").css("overflow", "hidden");
  }

  /**
   * Close Cart Sidebar
   */
  function closeCartSidebar() {
    $("#mini-cart-sidebar").removeClass("active");
    $(".overlay").removeClass("active");
    $("body").css("overflow", "");
  }

  // Cart toggle button click
  $(document).on("click", "#cart-toggle", function (e) {
    e.preventDefault();
    openCartSidebar();
  });

  // Close button click
  $(document).on("click", ".sidebar .close-icon", function (e) {
    e.preventDefault();
    closeCartSidebar();
  });

  // Overlay click
  $(document).on("click", ".overlay", function () {
    closeCartSidebar();
  });

  // Close sidebar with ESC key
  $(document).on("keydown", function (e) {
    if (e.key === "Escape" && $("#mini-cart-sidebar").hasClass("active")) {
      closeCartSidebar();
    }
  });

  // ============================================================================
  // UPDATE CART COUNT AND SUBTOTAL
  // ============================================================================

/**
 * Enhanced Update Cart Display Function
 * Add this to your ajax-add-to-cart.js file, replacing the existing updateCartDisplay function
 */

/**
 * Update cart count badge and subtotal in header and sidebar
 */
function updateCartDisplay(cartCount, cartSubtotal) {
    // Update cart count badge
    $("#header-cart-count").text(cartCount);

    // ✅ ENHANCED: Update subtotal in multiple locations
    // Update in sidebar footer
    if ($(".sidebar-footer .subtotal strong").length) {
        $(".sidebar-footer .subtotal strong").html(cartSubtotal);
    }
    
    // Update in mini cart total
    if ($(".woocommerce-mini-cart__total .amount").length) {
        $(".woocommerce-mini-cart__total .amount").replaceWith(cartSubtotal);
    }
    
    // Update any other subtotal displays
    $(".cart-subtotal .amount, .mini-cart-subtotal").html(cartSubtotal);

    // ✅ Handle empty cart state
    if (cartCount === 0) {
        // Update empty cart message
        if ($(".woocommerce-mini-cart").length) {
            $(".woocommerce-mini-cart").html('<p class="woocommerce-mini-cart__empty-message">No products in the cart.</p>');
        }
        // Hide checkout buttons
        $(".sidebar-footer, .woocommerce-mini-cart__buttons").hide();
    } else {
        $(".sidebar-footer, .woocommerce-mini-cart__buttons").show();
    }

    // Add bounce animation to cart icon
    $("#cart-toggle").addClass("bounce");
    setTimeout(function () {
        $("#cart-toggle").removeClass("bounce");
    }, 500);
    
    console.log('Cart Updated - Count:', cartCount, 'Subtotal:', cartSubtotal);
}

  // ============================================================================
  // ADD TO CART - VARIABLE PRODUCTS (with variations)
  // ============================================================================

  /**
   * Handle Add to Cart for Variable Products
   * Triggered from product overlay with variation selection
   */
  $(document).on("submit", "form.variations_form", function (e) {
    e.preventDefault();

    var $form = $(this);
    var $button = $form.find(".single_add_to_cart_button");

    // Get form data
    var productId =
      $form.find('input[name="product_id"]').val() ||
      $form.find('button[name="add-to-cart"]').val();
    var variationId = $form.find('input[name="variation_id"]').val();
    var quantity = $form.find('input[name="quantity"]').val() || 1;

    // Get selected variation attributes
    var variation = {};
    $form.find("select[name^='attribute_']").each(function () {
      var attrName = $(this).attr("name");
      var attrValue = $(this).val();
      variation[attrName] = attrValue;
    });

    // Validate variation selection
    if (!variationId || variationId == 0) {
      alert("Please select product options");
      return false;
    }

    // Disable button and show loading
    $button.prop("disabled", true).addClass("loading");
    var originalText = $button.text();
    $button.text("Adding...");

    // AJAX request
    $.ajax({
      type: "POST",
      url: ajax_add_to_cart_params.ajax_url,
      data: {
        action: "custom_add_to_cart",
        nonce: ajax_add_to_cart_params.nonce,
        product_id: productId,
        variation_id: variationId,
        quantity: quantity,
        variation: variation,
      },
      success: function (response) {
        if (response.success) {
          // Update cart display
          updateCartDisplay(
            response.data.cart_count,
            response.data.cart_subtotal
          );

          // Update mini cart HTML
          if (response.data.mini_cart_html) {
            $(".widget_shopping_cart_content").html(
              response.data.mini_cart_html
            );
          }

          // Close product overlay if exists
          $(".product-overlay.active").removeClass("active");
          $("body").css("overflow", "");

          // Open cart sidebar
          openCartSidebar();

          // Show success message
          showNotification("Product added to cart!", "success");

          // Reset form
          $form.find('select[name^="attribute_"]').prop("selectedIndex", 0);
          $form.find('input[name="variation_id"]').val("");
        } else {
          alert(response.data.message || "Unable to add product to cart");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("An error occurred. Please try again.");
      },
      complete: function () {
        // Re-enable button
        $button.prop("disabled", false).removeClass("loading");
        $button.text(originalText);
      },
    });

    return false;
  });

  // ============================================================================
  // ADD TO CART - SIMPLE PRODUCTS (no variations)
  // ============================================================================

  /**
   * Handle Add to Cart for Simple Products
   * Works with default WooCommerce add to cart buttons
   */
  $(document).on(
    "click",
    ".ajax_add_to_cart, .single_add_to_cart_button:not(.disabled)",
    function (e) {
      var $button = $(this);
      var $form = $button.closest("form.cart");

      // Skip if it's a variable product form (handled above)
      if ($form.hasClass("variations_form")) {
        return true;
      }

      e.preventDefault();

      var productId =
        $button.val() ||
        $button.data("product_id") ||
        $form.find('button[name="add-to-cart"]').val();
      var quantity =
        $form.find('input[name="quantity"]').val() ||
        $button.data("quantity") ||
        1;

      if (!productId) {
        console.error("No product ID found");
        return false;
      }

      // Disable button and show loading
      $button.prop("disabled", true).addClass("loading");
      var originalText = $button.text();
      $button.text("Adding...");

      // AJAX request
      $.ajax({
        type: "POST",
        url: ajax_add_to_cart_params.ajax_url,
        data: {
          action: "custom_add_to_cart",
          nonce: ajax_add_to_cart_params.nonce,
          product_id: productId,
          quantity: quantity,
        },
        success: function (response) {
          if (response.success) {
            // Update cart display
            updateCartDisplay(
              response.data.cart_count,
              response.data.cart_subtotal
            );

            // Update mini cart HTML
            if (response.data.mini_cart_html) {
              $(".widget_shopping_cart_content").html(
                response.data.mini_cart_html
              );
            }

            // Close product overlay if exists
            $(".product-overlay.active").removeClass("active");
            $("body").css("overflow", "");

            // Open cart sidebar
            openCartSidebar();

            // Show success message
            showNotification("Product added to cart!", "success");
          } else {
            alert(response.data.message || "Unable to add product to cart");
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("An error occurred. Please try again.");
        },
        complete: function () {
          // Re-enable button
          $button.prop("disabled", false).removeClass("loading");
          $button.text(originalText);
        },
      });

      return false;
    }
  );

  // ============================================================================
  // UPDATE CART QUANTITY
  // ============================================================================

  /**
   * Handle quantity update in mini cart
   * Updates cart without page reload
   */
  $(document).on("change", ".widget_shopping_cart .qty", function (e) {
    e.preventDefault();

    var $input = $(this);
    var cartItemKey = $input
      .attr("name")
      .replace(/cart\[(\w+)\]\[qty\]/g, "$1");
    var quantity = parseInt($input.val());

    // Validate quantity
    if (isNaN(quantity) || quantity < 0) {
      quantity = 0;
    }

    // Show loading state
    $input.prop("disabled", true);
    $(".widget_shopping_cart_content").addClass("updating");

    // AJAX request
    $.ajax({
      type: "POST",
      url: ajax_add_to_cart_params.ajax_url,
      data: {
        action: "update_cart_item",
        nonce: ajax_add_to_cart_params.nonce,
        cart_item_key: cartItemKey,
        quantity: quantity,
      },
      success: function (response) {
        if (response.success) {
          // Update cart display
          updateCartDisplay(
            response.data.cart_count,
            response.data.cart_subtotal
          );

          // Update mini cart HTML
          if (response.data.mini_cart_html) {
            $(".widget_shopping_cart_content").html(
              response.data.mini_cart_html
            );
          }

          // Show notification
          if (quantity === 0) {
            showNotification("Item removed from cart", "info");
          } else {
            showNotification("Cart updated", "success");
          }

          // Close sidebar if cart is empty
          if (response.data.cart_count === 0) {
            setTimeout(closeCartSidebar, 1000);
          }
        } else {
          alert(response.data.message || "Unable to update cart");
          // Reload to sync
          location.reload();
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("An error occurred. Please try again.");
        location.reload();
      },
      complete: function () {
        $input.prop("disabled", false);
        $(".widget_shopping_cart_content").removeClass("updating");
      },
    });
  });

  // ============================================================================
  // REMOVE FROM CART
  // ============================================================================

  /**
   * Handle remove item from cart
   * Removes product without page reload
   */

  jQuery(document.body).on('removed_from_cart', function() {
    console.log('Item removed — reloading page...');
    location.reload();
});

  $(document).on(
    "click",
    ".widget_shopping_cart .remove_from_cart_button",
    function (e) {
      e.preventDefault();

      var $button = $(this);
      var cartItemKey = $button.data("cart_item_key");

      // Confirm removal (optional - comment out if you don't want confirmation)
      if (!confirm("Are you sure you want to remove this item?")) {
        return false;
      }

      // Show loading state
      $button.addClass("removing");
      $(".widget_shopping_cart_content").addClass("updating");

      // AJAX request
      $.ajax({
        type: "POST",
        url: ajax_add_to_cart_params.ajax_url,
        data: {
          action: "remove_cart_item",
          nonce: ajax_add_to_cart_params.nonce,
          cart_item_key: cartItemKey,
        },
        success: function (response) {
          if (response.success) {
            // Update cart display
            updateCartDisplay(
              response.data.cart_count,
              response.data.cart_subtotal
            );

            // Update mini cart HTML
            if (response.data.mini_cart_html) {
              $(".widget_shopping_cart_content").html(
                response.data.mini_cart_html
              );
            }

            // Show notification
            showNotification(
              response.data.message || "Item removed from cart",
              "success"
            );

            // Close sidebar if cart is empty
            if (response.data.cart_count === 0) {
              setTimeout(closeCartSidebar, 1000);
            }
          } else {
            alert(response.data.message || "Unable to remove item");
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("An error occurred. Please try again.");
        },
        complete: function () {
          $button.removeClass("removing");
          $(".widget_shopping_cart_content").removeClass("updating");
        },
      });
    }
  );

  // ============================================================================
  // QUANTITY INCREMENT/DECREMENT BUTTONS
  // ============================================================================

  /**
   * Increment quantity button
   */
  $(document).on("click", ".quantity-plus", function (e) {
    e.preventDefault();
    var $input = $(this).siblings(".qty");
    var currentVal = parseInt($input.val()) || 0;
    var max = parseInt($input.attr("max")) || 999;

    if (currentVal < max) {
      $input.val(currentVal + 1).trigger("change");
    }
  });

  /**
   * Decrement quantity button
   */
  $(document).on("click", ".quantity-minus", function (e) {
    e.preventDefault();
    var $input = $(this).siblings(".qty");
    var currentVal = parseInt($input.val()) || 0;
    var min = parseInt($input.attr("min")) || 0;

    if (currentVal > min) {
      $input.val(currentVal - 1).trigger("change");
    }
  });

  // ============================================================================
  // NOTIFICATION SYSTEM
  // ============================================================================

  /**
   * Show notification message
   * @param {string} message - The message to display
   * @param {string} type - success, error, info, warning
   */
  function showNotification(message, type) {
    type = type || "info";

    // Create notification element if it doesn't exist
    if ($("#cart-notification").length === 0) {
      $("body").append(
        '<div id="cart-notification" class="cart-notification"></div>'
      );
    }

    var $notification = $("#cart-notification");
    $notification
      .removeClass("success error info warning")
      .addClass(type)
      .html(message)
      .fadeIn(300);

    // Auto hide after 3 seconds
    setTimeout(function () {
      $notification.fadeOut(300);
    }, 3000);
  }

  // ============================================================================
  // HANDLE WOOCOMMERCE FRAGMENTS (For compatibility)
  // ============================================================================

  /**
   * Listen for WooCommerce fragment refresh
   */
  $(document.body).on("wc_fragments_refreshed", function () {
    console.log("WooCommerce fragments refreshed");
  });

  /**
   * Update fragments manually
   */
  $(document.body).on(
    "added_to_cart",
    function (event, fragments, cart_hash, $button) {
      console.log("Product added to cart via WooCommerce");

      // Update cart count if fragment exists
      if (fragments && fragments["#header-cart-count"]) {
        $("#header-cart-count").replaceWith(fragments["#header-cart-count"]);
      }

      // Open sidebar
      openCartSidebar();
    }
  );

  // ============================================================================
  // PRODUCT OVERLAY FUNCTIONALITY
  // ============================================================================

  /**
   * Open product options overlay
   */
  $(document).on("click", ".select-options-btn", function (e) {
    e.preventDefault();
    var $overlay = $(this).closest(".product-card").find(".product-overlay");
    if ($overlay.length) {
      $overlay.addClass("active");
    }
  });

  /**
   * Close product overlay
   */
  $(document).on("click", ".close-overlay", function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).closest(".product-overlay").removeClass("active");
    $("body").css("overflow", "");
  });

  /**
   * Close overlay when clicking on overlay background
   */
  $(document).on("click", ".product-overlay", function (e) {
    if ($(e.target).hasClass("product-overlay")) {
      $(this).removeClass("active");
      $("body").css("overflow", "");
    }
  });

  /**
   * Prevent closing when clicking inside overlay content
   */
  $(document).on("click", ".product-overlay .add-to-cart", function (e) {
    e.stopPropagation();
  });

  // Close overlay with ESC key
  $(document).on("keydown", function (e) {
    if (e.key === "Escape" && $(".product-overlay.active").length) {
      $(".product-overlay.active").removeClass("active");
      $("body").css("overflow", "");
    }
  });

  // ============================================================================
  // PREVENT DOUBLE SUBMISSION
  // ============================================================================

  /**
   * Prevent multiple rapid clicks on add to cart buttons
   */
  var isProcessing = false;

  $(document).on(
    "click",
    ".single_add_to_cart_button, .ajax_add_to_cart",
    function (e) {
      if (isProcessing) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
      }
    }
  );

  // Reset processing flag on AJAX complete
  $(document).ajaxComplete(function (event, xhr, settings) {
    if (
      settings.data &&
      settings.data.indexOf("action=custom_add_to_cart") > -1
    ) {
      isProcessing = false;
    }
  });

  $(document).ajaxSend(function (event, xhr, settings) {
    if (
      settings.data &&
      settings.data.indexOf("action=custom_add_to_cart") > -1
    ) {
      isProcessing = true;
    }
  });

  // ============================================================================
  // VARIATION FORM ENHANCEMENT
  // ============================================================================

  /**
   * Enable/disable add to cart button based on variation selection
   */
  $(document).on(
    "show_variation",
    ".variations_form",
    function (event, variation) {
      var $form = $(this);
      var $button = $form.find(".single_add_to_cart_button");

      // Enable button and update price
      $button.prop("disabled", false).removeClass("disabled");

      console.log("Variation selected:", variation.variation_id);
    }
  );

  $(document).on("hide_variation", ".variations_form", function () {
    var $form = $(this);
    var $button = $form.find(".single_add_to_cart_button");

    // Disable button when no variation selected
    $button.prop("disabled", true).addClass("disabled");
  });

  // ============================================================================
  // CART PAGE COMPATIBILITY (if using AJAX on cart page)
  // ============================================================================

  /**
   * Handle cart page quantity updates
   */
  $(document).on("click", ".cart_item .quantity .qty", function () {
    var $input = $(this);

    // Add a small delay to avoid too many requests
    clearTimeout($input.data("timeout"));
    $input.data(
      "timeout",
      setTimeout(function () {
        $("button[name='update_cart']")
          .prop("disabled", false)
          .trigger("click");
      }, 1000)
    );
  });

  // ============================================================================
  // INITIALIZE ON PAGE LOAD
  // ============================================================================

  $(document).ready(function () {
    console.log("AJAX Add to Cart System Initialized");

    // Enable AJAX add to cart for standard WooCommerce buttons
    $(".single_add_to_cart_button").removeClass("disabled");

    // Initialize WooCommerce variations form if exists
    if (typeof wc_add_to_cart_variation_params !== "undefined") {
      $(".variations_form").each(function () {
        $(this).wc_variation_form();
      });
    }

    // Check if cart is already open (from URL parameter)
    if (
      window.location.hash === "#cart" ||
      window.location.search.indexOf("show-cart=1") > -1
    ) {
      openCartSidebar();
    }

    // Add smooth scroll behavior
    $('a[href*="#cart"]').on("click", function (e) {
      e.preventDefault();
      openCartSidebar();
    });

    // Refresh cart count on page load
    refreshCartCount();
  });

  // ============================================================================
  // REFRESH CART COUNT
  // ============================================================================

  /**
   * Refresh cart count from server
   * Useful when navigating back to page
   */
  function refreshCartCount() {
    $.ajax({
      type: "POST",
      url: ajax_add_to_cart_params.wc_ajax_url.replace(
        "%%endpoint%%",
        "get_refreshed_fragments"
      ),
      success: function (response) {
        if (response && response.fragments) {
          $.each(response.fragments, function (key, value) {
            $(key).replaceWith(value);
          });
        }
      },
      error: function (xhr, status, error) {
        console.log("Could not refresh cart fragments:", error);
      },
    });
  }

  // ============================================================================
  // UTILITY FUNCTIONS
  // ============================================================================

  /**
   * Debounce function to limit rate of function execution
   */
  function debounce(func, wait) {
    var timeout;
    return function () {
      var context = this,
        args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(function () {
        func.apply(context, args);
      }, wait);
    };
  }

  /**
   * Format price (optional - if you need custom formatting)
   */
  function formatPrice(price) {
    // Customize this based on your currency format
    return "$" + parseFloat(price).toFixed(2);
  }

  // ============================================================================
  // EXPOSED FUNCTIONS (for external use)
  // ============================================================================


  // ============================================================================
// GLOBAL VARIATION FORM INITIALIZATION
// ============================================================================

function initAllVariationForms() {
    if (typeof wc_add_to_cart_variation_params === 'undefined') {
        return;
    }

    $('.variations_form:not(.initialized)').each(function() {
        var $form = $(this);
        try {
            $form.addClass('initialized');
            $form.wc_variation_form();
            $form.trigger('check_variations');
        } catch (error) {
            console.error('Error initializing variation form:', error);
        }
    });
}

var observer = new MutationObserver(function(mutations) {
    var shouldInit = false;
    mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length > 0) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) {
                    if ($(node).find('.variations_form').length > 0 || 
                        $(node).hasClass('variations_form')) {
                        shouldInit = true;
                    }
                }
            });
        }
    });
    if (shouldInit) {
        setTimeout(initAllVariationForms, 100);
    }
});

$(document).ready(function() {
    initAllVariationForms();
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.data) {
            var data = settings.data;
            if (data.indexOf('action=load_products') > -1 || 
                data.indexOf('action=load_more_products') > -1) {
                setTimeout(initAllVariationForms, 200);
            }
        }
    });
});

window.initVariationForms = initAllVariationForms;

  // Make functions available globally if needed
  window.cartSystem = {
    openSidebar: openCartSidebar,
    closeSidebar: closeCartSidebar,
    showNotification: showNotification,
    refreshCartCount: refreshCartCount,
  };
})(jQuery);


