jQuery(document).ready(function ($) {
	$(".ajax-add-to-cart").on("click", function (e) {
		e.preventDefault();
		var button = $(this);
		var product_id = button.data("productId");
		var variation_id = button.data("variation");
		$.ajax({
			url: ajax_cart_params.ajax_url,
			type: "POST",
			data: {
				action: "wicklow_ajax_add_to_cart",
				product_id: product_id,
				variation_id: variation_id,
				nonce: ajax_cart_params.nonce,
			},
			success: function (response) {
				if (response.success) {
					// Trigger WooCommerce cart fragments refresh
					if (typeof wc_cart_fragments_params !== "undefined") {
						console.log(wc_cart_fragments_params);
						$(document.body).trigger("added_to_cart");
						alert("Added to cart!");
					}
				} else {
					var msg =
						response.data && response.data.message
							? response.data.message
							: "Could not add to cart.";
					console.log(msg);
				}
			},
		});
	});
});
