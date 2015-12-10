//$(document).on('ready pjax:success', function(){
$(function(){

	$('.detail-view .col-toggel').click(function(e){
		$('#'+$(this).attr('data-toggle')).slideToggle(200);
		$(this).find('.ace-icon').toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
		e.preventDefault();
	});

	//var client = new ZeroClipboard( $("#copy") );

	$('.pricetest').click(function(e){

		$.ajax({
			//async: false,
			url: "http://ebayimages.x10host.com/price.php",
			dataType: 'jsonp',
			//type:'POST',
			success: function( result ) {
		    	console.log(result.fullname);
		    },
		    error: function( xhr, status, errorThrown ) {
		        alert( "Sorry, there was a problem!" );
		        console.log( "Error: " + errorThrown );
		        console.log( "Status: " + status );
		        console.dir( xhr );
		    },
		});
		e.preventDefault();
	});

	listingImageInit();
	//deleteLstImage();
	serializeImgs();

	$('.sortable').disableSelection();
	//$('.hack-bar').hide();
});
function serializeImgs(){
	$('.serializeImgs').click(function(e){
		$(this).parents('.widget-main').find('.sortable.grid').sortable('disable');
		$(this).parents('.widget-main').find('.listing-images-wrap').hide();
		$(this).parents('.widget-main').find('.listing-images-loading').show();
		var newSort = new Array();
		var newImageSorted = $(this).parents('.widget-main').find('.lst-img-container img.countable');
		if(newImageSorted.length!=0){
			newImageSorted.each(function(index,image){
				newSort.push(image.src);
			});
		}else{
			newSort = null;
		}


		updateListingImgInfo($(this).data().productId, $(this).data().ebayId, newSort);
		console.log(newSort);
	});
}
function updateListingImgInfo(productID,ebayID,newSort){
	$.ajax({
		url: '/product/update-lst-img-info',
		type: 'POST',
		data: {productID: productID,ebayID:ebayID,listingImages:newSort}
	})
	.done(function(result) {
		if(result){
			$.pjax.reload({container: '#listing-images-pjax-'+ebayID});
		}else{
			alert('error while saving');
		}
		console.log(result);
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function deleteLstImage(){
	$('.btn-i-delete-images').click(function(e){
		e.preventDefault(e);
		$(this).parents('.sortable-item').find('img').toggleClass('discountable').toggleClass('countable');
	});
}

function listingImageInit(){
	listingImageTemp = new Array();
	progressBarInit = 8;
}


function uploadAjaxHack(index,files,progressBar){
	if(index<files.length){
		data = new FormData();
    data.append("file", files[index]);

    $.ajax({
        data: data,
        type: "POST",
        //async: false,
        url: 'http://uploads.im/api',
        cache: false,
        contentType: false,
        processData: false,
        success: function(url) {
        	if(url.status_code==200){
        		//console.log(url.data.img_url);
        		progressBarInit+=Math.floor((Math.random() * 18) + 1);
        		progressBar.html(progressBarInit+'%').css('width',progressBarInit+'%');
        		listingImageTemp.push(url.data.img_url);
        		uploadAjaxHack(index+1,files,progressBar);
        	}else{
        		deferredUpload.resolve(false);
        	}
				}
    });
	}else{
		deferredUpload.resolve(true);
	}
	//return true;
}

function saveListingImagesInfo(ebayID,productID){
	console.log(listingImageTemp);
	$.ajax({
		data:{ebayID:ebayID,productID:productID,imageArray:listingImageTemp},
		type:'POST',
		url:'/product/save-lst-img-info',
		success: function(result){
			if(result){
				$.pjax.reload({container: '#listing-images-pjax-'+ebayID});
				deferredSave.resolve(true);
			}else{
				deferredSave.resolve(false);
			}
		}
	});
}
/*function uploadAjaxHack(files){
	var result = true;
	x=1;
	$.each(files, function(index,file){
		data = new FormData();
	    data.append("file", file);

	    $.ajax({
	        data: data,
	        type: "POST",
	        //async: false,
	        url: 'http://uploads.im/api',
	        cache: false,
	        contentType: false,
	        processData: false,
	        success: function(url) {
	        	if(url.status_code==200){
	        		listingImageTemp.push(url.data.img_url);
	        		console.log('1');
	        		x=x+1;
	        		$('#test').html(x);
	        		//x = x+10;
	        		//widget.find('.progress-bar').width(x);
	        		//saveListingImagesInfo(ebayID,productID);
	        		//progressBar.html('100%').width('100%');

	        		//console.log(listingImageTemp);
	        	}else{
	        		result = false;
	        	}

	        }
	    });
	});
	return result;

}*/
