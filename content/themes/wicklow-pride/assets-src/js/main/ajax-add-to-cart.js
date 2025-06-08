let ajax_add_to_cart = function () {
	// jQuery(document).ready(function ($) {
	// 	$(".product-card__body button").on("click", function (e) {
	// 		e.preventDefault();
	// 		var button = $(this);
	// 		console.log(button.data("productId"));
	// 		console.log(ajax_cart_params);
	// 		var product_id = button.data("productId");
	// 		$.ajax({
	// 			url: ajax_cart_params.ajax_url,
	// 			type: "POST",
	// 			data: {
	// 				action: "wicklow_pride_ajax_add_to_cart",
	// 				product_id: product_id,
	// 				nonce: ajax_cart_params.nonce,
	// 			},
	// 			success: function (response) {
	// 				console.log(response);
	// 				if (response.success) {
	// 					// Optionally update cart UI here
	// 					alert("Added to cart!");
	// 				} else {
	// 					console.log("Could not add to cart.");
	// 					console.log(response.error);
	// 				}
	// 			},
	// 		});
	// 	});
	// });
};

export { ajax_add_to_cart };
