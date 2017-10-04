/*Update Qty*/
jQuery('.add-to-cart button').on('click', function(){
	var current = jQuery('.header-cart-icon').text();
	var newcartval = parseInt(current) + 1;
	jQuery('.header-cart-icon').text(newcartval);
});

/*Toggle Search*/
jQuery('.header-search i').on('click', function(){
	jQuery('.productsearch').slideToggle('slow');
});
