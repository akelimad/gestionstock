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

      var product = _.findWhere(products, {id : id});
      var fotorama_image=[];
      $('.fotorama').remove();
      $('.modal-body .row .col-md-6 .imagesProduct').append('<div class="fotorama" data-nav="thumbs" data-allowfullscreen="true" data-keyboard="true"></div>');

      $(".name").empty().html(product.name);
      $(".description").empty().html("Description : "+product.description);
      $(".size-inch").empty().html("<i class='fa fa-forward'></i> Taille(inch) : "+product.sizeInch);
      $(".size-cm").empty().html("<i class='fa fa-forward'></i> Taille(cm) : "+product.sizeCm);
      $(".color").empty().html("Color : " + product.color).css("background", "#"+product.color);
      $(".composition").empty().html("Composition : "+product.composition);
      $(".form").empty().html("Form : "+product.form);
      $(".weight").empty().html("<i class='fa fa-forward'></i> Poids(kg) : "+product.weight);
      $(".unit-price").empty().html("<i class='fa fa-forward'></i> Prix unitaire  : "+product.unitPrice + " $");
      $(".wholesale-price").empty().html("<i class='fa fa-forward'></i> Prix grossiste  : "+product.wholesalePrice + " $");
      $(".special-price").empty().html("<i class='fa fa-forward'></i> Prix spécial  : "+product.specialPrice + " $");
      $(".internet-price").empty().html("<i class='fa fa-forward'></i> Prix internet  : "+product.internetPrice + " $");
      $(".fotorama").empty();
      for (var i = 0; i < product.images.length; i++) {
        var img={'img'  : $(".imagesProduct").data("image-url")+product.images[i],
                 'thumb': $(".imagesProduct").data("image-url")+product.images[i]
        }
        fotorama_image.push(img);
      }
      $(".category").empty().html("<i class='fa fa-tag'></i> "+product.category);
      $(".package").empty().html("package : "+product.package);
      $(".provider").empty().html("provider : "+product.provider);

      $('.fotorama').fotorama({'data':fotorama_image});
      
      $('#productModal').modal('show'); 
  }

}
	
  