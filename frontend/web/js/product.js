$(function(){


	//subProduct = false;

	/*spinners*/
	$('#product-stock_qty').ace_spinner({
        max: 999,
				min: 0,
        step: 9,
	});


	/*$('#product-qty_per_order').ace_spinner({
        max: 10000,
		min: 1,
        step: 1,
	});*/

	$('.field-product-qty_per_order .ace-spinner .btn').addClass('btn-danger');
	$('.field-product-qty_per_order .ace-spinner .btn i.ace-icon').removeClass('icon-only');

	$('.ace-spinner .ace-spinner .btn').addClass('btn-danger');
	$('.ace-spinner .ace-spinner .btn i.ace-icon').removeClass('icon-only');

	/*ajax form for create category supplier and stock location*/
	createOtherAjax('supplier');
	createOtherAjax('category');
	createOtherAjax('stocklocation');



	/*去除autocomplte的提示bug*/
	$( "#main-sku" ).on( "autocompleteclose", function( event, ui ) { $(".ui-helper-hidden-accessible").remove();} );
	$( "#main-sku" ).on( "autocompletecreate", function( event, ui ) { $(".ui-helper-hidden-accessible").remove();} );

	autoSKU();
});
function autoSKU(){
	$('#auto-sku').click(function(){
		var newId = Math.random().toString().substr(2,8);
		var catCode=$('#product-category_id').find(':selected').data('catCode');
		//console.log(catCode);
		if(typeof catCode!=='undefined'){
			$('#product-sku').val(catCode+'-'+newId+'-'+'M');
		}

	});
}




function createOtherAjax(indentifier){
	$('button#close-modal-'+indentifier).click(function(){
		$('#create-'+indentifier).modal('hide');
		$('.'+indentifier+'-form').show();
		$('#'+indentifier+'-form')[0].reset();
		$('#loading-'+indentifier).hide();
		$.pjax.reload({container: '#'+indentifier+'-selection'});

	});
	$('form#'+indentifier+'-form').on('beforeSubmit', function(){
		var form = $(this);

		if (form.find('.has-error').length) {
	          return false;
	     }
	     // submit form
	     $('.'+indentifier+'-form').hide();
	     $('#loading-'+indentifier).fadeIn('slow');

	     $.ajax({
	          url: form.attr('action'),
	          type: 'post',
	          data: form.serialize(),
	          success: function (response) {
	               // do something with response
	               var result = JSON.parse(response);

	               if(result[0]==true){
	               		$('p.loading').html(result[1]+' created');
	               		$('button#close-modal-'+indentifier).html('click to continue').fadeIn('slow');
	               }else{
	               		alert('Failed');
	               }
	          }
	     });
	     return false;

	});

}
function uploadComplete(response){
	$("input[name='Product[main_image]']").val(response.url);
	//$('#main_image_ph').attr('src',response.url);
	//$.pjax.reload({container: '#product-image'});
	console.log(response.url);
}
// ajax upload main image file
// function uploadMainImage(files){
// 	data = new FormData();
// 	data.append("file", files['0']);
// 	$.ajax({
// 			data: data,
// 			type: "POST",
// 			//async: false,
// 			url: 'http://uploads.im/api',
// 			cache: false,
// 			contentType: false,
// 			processData: false,
// 			success: function(url) {
// 				if(url.status_code==200){
// 					$('.progress-bar').html('100%').css('width','100%');
// 					$('#main_image_ph').attr('src',url.data.img_url);
// 					$('#product-main_image').val(url.data.img_url);
// 					$.pjax.reload({container: '#product-image'});
//
// 				}else{
// 					$('.progress-bar').html('Error').css('width','100%').toggleClass('progress-bar-success').toggleClass('progress-bar-danger');
// 				}
// 			}
// 	});
//
// }
