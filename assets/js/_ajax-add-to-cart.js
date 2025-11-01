jQuery(function ($) {
  $(".ajax_add_to_cart").on("click", function (e) {
    e.preventDefault();

    let product_id = $(this).data("product_id");
    let quantity = $(this).data("quantity") || 1;

    $.ajax({
      type: "POST",
      url: ajax_add_to_cart_params.wc_ajax_url.replace("%%endpoint%%", "add_to_cart"),
      data: {
        product_id: product_id,
        quantity: quantity,
      },
      success: function (response) {
        if (response && response.fragments) {
          // Update fragments (cart count, mini cart, etc.)
          $.each(response.fragments, function (key, value) {
            $(key).replaceWith(value);
          });

          // Optional success message
          alert("Product added to cart!");
        } else {
          alert("Failed to add product.");
        }
      },
      error: function (err) {
        console.log(err);
      },
    });
  });
});
