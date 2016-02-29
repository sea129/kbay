$(function(){

	$('#product-qty_per_order').ace_spinner({
    max: 10000,
		min: 2,
		step: 1,
	});
	mainSKU = $('#product-sku').val();
	qty_length = $('#product-qty_per_order').val().length;
	singleCost = $('#product-cost').val();
	singleWeight = $('#product-weight').val();

	changeCost();
	changeWeight();

	//$("input[name='Product[packaging_id]']:checked").prop('checked',false);
	//console.log(availablePackaging);
	$('#product-qty_per_order').closest('.ace-spinner').on('changed.fu.spinbox', updateForm);

	$('#auto-sku').click(function(){
		changeSKU();
	});

	$('#auto-name').click(function(){
		changeName();
	});

});



function updateForm(){
	changeCost();
	changeWeight();
}

function changeWeight(){
	$('#product-weight').val(singleWeight*$('#product-qty_per_order').val());
}
function changeCost(){
	$('#product-cost').val(singleCost*$('#product-qty_per_order').val());
}

function changeName(){
	$('#product-name').val($('#product-qty_per_order').val()+'X '+$('#product-name').val());
}
function changeSKU(){
	if(mainSKU.slice(-2)==='-M'){
		$('#product-sku').val(mainSKU.split('-M')[0]+'-B'+$('#product-qty_per_order').val());
	}else{
		if(typeof $('#product-sku').val().split('-B')['1']!=='undefined'){
			$('#product-sku').val($('#product-sku').val().split('-B')[0]+'-B'+$('#product-qty_per_order').val());
		}else{
			$('#product-sku').val(mainSKU+'-B'+$('#product-qty_per_order').val());
		}
	}
}
