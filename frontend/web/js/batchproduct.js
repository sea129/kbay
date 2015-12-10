$(function(){

	$('#batchproduct-qty_per_order').ace_spinner({
        max: 10000,
		min: 1,
        step: 1,
	});

	qty_length = $('#batchproduct-qty_per_order').val().length;
	singleCost = $('#batchproduct-cost').val();
	singleWeight = $('#batchproduct-weight').val();
	$('#batchproduct-qty_per_order').closest('.ace-spinner').on('changed.fu.spinbox', updateForm);

	
	/*qty_length = $('#batchproduct-qty_per_order').val().length;
	$('#batchproduct-qty_per_order').closest('.ace-spinner').on('changed.fu.spinbox', changeName);*/

	autoGenerateSwitch();

	changeSKU();
	changeCost();
	changeWeight();

	availablePackaging = getAllPackaging();
	updatePackagings();
	//console.log(availablePackaging);
	$('#batchproduct-is_trackable').on('change',updatePackagings);
	
});
function updatePackagings(){

	suitableID = [];
	var weight = $('#batchproduct-weight').val();
	if($('#batchproduct-is_trackable').is(":checked")){
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
	$.each($(".radio-inline input[name='BatchProduct[packaging_id]']"),function(key,obj){
				
		if($.inArray(obj['value'],suitableID)!=-1){
			
			$(this).parent().show();
		}else{
			$(this).parent().hide();
		}
	});

}

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
function updateForm(){
	changeSKU();
	changeName();
	changeCost();
	changeWeight();
	updatePackagings();
}

function changeWeight(){
	$('#batchproduct-weight').val(singleWeight*$('#batchproduct-qty_per_order').val());
}
function changeCost(){
	$('#batchproduct-cost').val(singleCost*$('#batchproduct-qty_per_order').val());
}

function changeName () {
	if($('#auto-generate').is(':checked')){
		
		$('#batchproduct-name').val($('#batchproduct-qty_per_order').val()+$('#batchproduct-name').val().substr(qty_length));
		qty_length = $('#batchproduct-qty_per_order').val().length;
	}
}
function autoName(){
	if($('#auto-generate').is(':checked')){

		$('#batchproduct-name').val($('#batchproduct-qty_per_order').val()+'X '+$('#batchproduct-name').val());
	}
}
function changeSKU(){
	temp = $('#batchproduct-sku').val();
	tempArr = temp.split('-X');
	$('#batchproduct-sku').val(tempArr[0]+'-X'+$('#batchproduct-qty_per_order').val());
}
function autoGenerateSwitch () {
	$('#auto-generate').change(function(){
		if($(this).is(":checked")){
			$('#batchproduct-name').prop('readonly',true);
			autoName();
		}else{
			$('#batchproduct-name').prop('readonly',false);
		}
	});
}

/*function updatePackagings(){
	
		
	weight = $('#batchproduct-weight').val();
	if($('#batchproduct-is_trackable').is(":checked")){
		track = 1;
	}else{
		track = 0;
	}


	$.ajax({
		url: '/product/find-all-packaging',
		type: 'post',
		data: {'weight':weight, 'track': track},
		success:function(response){
			
			
			available = [];
			$.each(response,function(key,value){
				available.push(key);
			});
			
			$.each($(".radio-inline input[name='BatchProduct[packaging_id]']"),function(key,obj){
				
				if($.inArray(obj['value'],available)!=-1){
					
					$(this).parent().show();
				}else{
					$(this).parent().hide();
				}
			});

			$(".radio-inline input[name='BatchProduct[packaging_id]']:visible:first").prop("checked", true);
		}
	});
	
}*/