jQuery(document).ready(function ($) {
	$("body").removeClass("admin-bar");
	$("ul.site-header-cart li:first-child").on("click", function (e) {
		e.preventDefault();
		$("ul.site-header-cart").toggleClass("expanded");
	});
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
					// Always trigger WooCommerce cart fragments refresh
					$(document.body).trigger("added_to_cart");
					// Optionally also trigger this for extra reliability:
					$(document.body).trigger("wc_fragment_refresh");
					alert("Added to cart!");
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
