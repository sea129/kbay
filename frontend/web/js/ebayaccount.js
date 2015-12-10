$(function(){
	$('#sync-button').click(function(){
		flag = true;
		$.ajax({
			//async: false,
			url: "sync-listing",
			data: {
				id: id,
			},
			dataType : "json",
			//type:'POST',
			success: function( json ) {
		        //$('#result').html(json);
		        flag = false;
		        setTimeout(function(){
		        	$('#ajax-progress').css("width","100%");
		        },700);
		        result = json;
		        $('#updated-listings').html(result.updated.length);
		        $('#added-listings').html(result.added.length);
		        $('#deleted-listings').html(result.deleted.length);

		        formList(result.updated,$('#updated-sku ul'));
		        formList(result.added,$('#added-sku ul'));
		        formList(result.deleted,$('#deleted-sku ul'));
		        //console.log(result.updated.length);
		        
		    },
		    error: function( xhr, status, errorThrown ) {
		        alert( "Sorry, there was a problem!" );
		        console.log( "Error: " + errorThrown );
		        console.log( "Status: " + status );
		        console.dir( xhr );
		    },



		});
	});

});

function progressbar(width){
	//$('#ajax-progress').css("width",width+"%");
	
	if(flag && (width<80)){
		$('#ajax-progress').css("width",width+"%");
		//width=width+Math.floor((Math.random() * 15) + 1);
		width=width+Math.floor((Math.random() * 3) + 1);;
		setTimeout(function(){progressbar(width)},40);
		//alert(width);
	}
	
	
}

function formList(array,ul){
	$.each(array,function(i){
		var li = $('<li/>')
			.append("<i class='ace-icon fa fa-angle-right bigger-110'></i>")
			.append(array[i])
	        .appendTo(ul);
	});
	
}