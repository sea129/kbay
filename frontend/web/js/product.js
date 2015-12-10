$(function(){

	newId = Date.now().toString().substr(Math.floor((Math.random() * 5) + 1),5);
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

	setSKU('OTR',newId,$('#product-qty_per_order').val());
	
	
	/*GET SKU FIRST*/
	getSKU();

	
	$('.field-product-qty_per_order .ace-spinner .btn').addClass('btn-danger');
	$('.field-product-qty_per_order .ace-spinner .btn i.ace-icon').removeClass('icon-only');
	
	$('.ace-spinner .ace-spinner .btn').addClass('btn-danger');
	$('.ace-spinner .ace-spinner .btn i.ace-icon').removeClass('icon-only');

	/*ajax form for create category supplier and stock location*/
	createOtherAjax('supplier');
	createOtherAjax('category');
	createOtherAjax('stocklocation');

	availablePackaging = getAllPackaging();
	/*updatePackagings 包装和邮寄选项*/
	updatePackagings();
	$('#product-weight').add('#product-is_trackable').on('change',updatePackagings);

	//linkProduct();

	/*去除autocomplte的提示bug*/
	$( "#main-sku" ).on( "autocompleteclose", function( event, ui ) { $(".ui-helper-hidden-accessible").remove();} );
	$( "#main-sku" ).on( "autocompletecreate", function( event, ui ) { $(".ui-helper-hidden-accessible").remove();} );

	/*$('#product-productimage').on('fileuploaded', function(event, data, previewId, index){
		$.pjax.reload({container: '#product-image'});
	});*/
	
});

function getAllPackaging(){
	var all;
	$.ajax({
		url: '/product/find-all-packaging',
		type: 'post',
		async: false,
		success:function(response){
			//console.log(response);
			all = response;
		}
	});
	return all;
}
/*function linkProduct(){
	$('#link-product').click(function(){
		mainSKU = $("#main-sku").val();
		$.ajax({
			url: '/product/get-id',
			data: { 'sku':mainSKU },
			type: 'post',
			success:function(product){
				if(product==false){
					alert('Wrong SKU');
				}else{

					subProduct = true;
					$('#main-product-id').val(product);
					$('#product-sku').val(product['sku']).prop('readonly', true);
					$('#product-name').val($('#product-qty_per_order').val()+'X '+product['name']).prop('readonly', true);
					$('#product-stock_qty').val(null).prop('readonly', true);
					$('#product-stock_qty').ace_spinner('disable');
					$('.field-product-stock_qty .btn').hide();
					$('#product-cost').val(product['cost']).prop('readonly', true);
					$('#product-supplier_id').val(product['supplier_id']).prop('readonly', true);
					$('#product-category_id').val(product['category_id']).prop('readonly', true);
					$('#product-stock_location').val(product['stock_location']).prop('readonly', true);


					console.log(product['name']);
				}
				
			}
		});
	});
}*/
function setSKU(cate,value,qty){
	$('#product-sku').val(cate+'-'+value+'-'+'X'+qty);
}
function getSKU(){

	$('#product-category_id').change(function(){
		$.ajax({
			url: '/category/get-code',
			data:{'id':$(this).val()},
			type:'post',
			success:function(response){
				if(response!='error'){
					//$('#product-sku').val(response+'-'+newId);
					setSKU(response,newId,$('#product-qty_per_order').val());
				}else{
					//console.log(typeof($('#product-category_id').val()));
					if($('#product-sku').val()){
						setSKU('OTR',newId,$('#product-qty_per_order').val());
					}else{
						alert('Category: '+$('#product-category_id').val()+' not found');
					}
					
				}
			}
		});
	});

/*	$('#product-qty_per_order').closest('.ace-spinner').on('changed.fu.spinbox', function () {
		temp = $('#product-sku').val();
		tempArr = temp.split('-X');
		$('#product-sku').val(tempArr[0]+'-X'+$('#product-qty_per_order').val());
		if(subProduct==true){
			tempName = $('#product-name').val().substr(3);
			$('#product-name').val($('#product-qty_per_order').val()+'X '+tempName);

		}
	});*/
}

/*function updatePackagings(){
	
	$('#product-weight').add('#product-is_trackable').change(function(){
		
		weight = $('#product-weight').val();
		if($('#product-is_trackable').is(":checked")){
			track = 1;
		}else{
			track = 0;
		}

		$.ajax({
			url: '/product/find-packaging',
			type: 'post',
			data: {'weight':weight, 'track': track},
			success:function(response){
				
				
				available = [];
				$.each(response,function(key,value){
					available.push(key);
				});
				
				$.each($(".radio-inline input[name='Product[packaging_id]']"),function(key,obj){
					
					if($.inArray(obj['value'],available)!=-1){
						
						$(this).parent().show();
					}else{
						$(this).parent().hide();
					}
				});

				$(".radio-inline input[name='Product[packaging_id]']:visible:first").prop("checked", true);
			}
		});
	});
}*/

function updatePackagings(){

	suitableID = [];
	var weight = $('#product-weight').val();
	if($('#product-is_trackable').is(":checked")){
		$.each(availablePackaging,function(key,obj){
			
			if(obj['type']=='track parcel' && (parseInt(obj['weight_offset'])>=weight)){
				
				suitableID.push(obj['id']);
			}
		});
	}else{
		$.each(availablePackaging,function(key,obj){
			if(obj['type']!='track parcel' && parseInt(obj['weight_offset'])>=weight){
				suitableID.push(obj['id']);
			}
		});
	}
	//console.log(suitableID);
	$.each($(".radio-inline input[name='Product[packaging_id]']"),function(key,obj){
				
		if($.inArray(obj['value'],suitableID)!=-1){
			
			$(this).parent().show();
		}else{
			$(this).parent().hide();
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
