var Product = {
  	init: function(products){
  		var me=this;
  		// $.each(products, function( index, value ) {
  		//   console.log(index, value);
  		// });
  		$(".showProduct").click(function(){
	        var id= $(this).data("id");
	        me.showProduct(id);
	    });
  	},
  	showProduct: function(id){
      $(".fotorama").empty();
  		var product = _.findWhere(products, {id : id});
  		$(".name").empty().html(product.name);
  		$(".description").empty().html("description : "+product.description);
  		$(".size-inch").empty().html("size inch : "+product.sizeInch);
  		$(".size-cm").empty().html("size cm : "+product.sizeCm);
  		$(".color").empty().html("color : "+product.color);
  		$(".composition").empty().html("composition : "+product.composition);
  		$(".form").empty().html("form : "+product.form);
  		$(".weight").empty().html("weight : "+product.weight);
  		$(".unit-price").empty().html("internet price : "+product.unitPrice);
  		$(".wholesale-price").empty().html("wholesale price : "+product.wholesalePrice);
  		$(".special-price").empty().html("special price : "+product.specialPrice);
  		$(".special-price").empty().html("special price : "+product.specialPrice);
      $(".internet-price").empty().html("internet price : "+product.internetPrice);
      for (var i = 0; i < product.images.length; i++) {
        $(".fotorama").append('<a href="/gestionstock/web/uploads/images/'+product.images[i]+'"><img src="/gestionstock/web/uploads/images/'+product.images[i]+'"></a>'); 
      }
      $('.fotorama').find('a:first').addClass('sp-default');

  		$(".category").empty().html("Category : "+product.category);
  		$(".package").empty().html("package : "+product.package);
  		$(".provider").empty().html("provider : "+product.provider);

      $('.fotorama').smoothproducts();

  		$('#productModal').modal('show'); 
	}

}
	
  