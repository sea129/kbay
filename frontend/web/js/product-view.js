$(document).on('pjax:success', function(data, status, xhr, options) {
  // Prevent default timeout redirection behavior
  //console.log(data.target.id);
	$('#'+data.target.id+' .btn-i-delete-images').click(function(e){
		e.preventDefault(e);
		$(this).parents('.sortable-item').find('img').toggleClass('discountable').toggleClass('countable');
	});
});
$(document).on('ready', function(){
	$('.detail-view .col-toggel').click(function(e){
		$('#'+$(this).attr('data-toggle')).slideToggle(200);
		$(this).find('.ace-icon').toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
		e.preventDefault();
	});
  $.pjax.defaults.timeout = 5000;
	listingImageInit();
	deleteLstImage();
	serializeImgs();

	$('.sortable').disableSelection();

  $('#similar-search').click(function(){
    //var itemId = $('#similar-item-id').value;
    $('#listing-details').hide();
    $('#similar-search-loading').show();
    $.ajax({
      url: '/listing/search-similar',
      type: 'POST',
      //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {itemId: $('#similar-item-id').val()}
    })
    .done(function(result) {

      console.log(result.title,result.price,result.categoryID);
      $('#item-cate-id').val(result.categoryID);
      $('#item-title').val(result.title);
      $('#item-price').val(result.price);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      $('#similar-search-loading').hide();
      $('#listing-details').show();
      console.log("complete");
    });

  });

  $('#btn-revise-listing-submit').click(function(){
    $('#revise-form').hide();
    $('#revise-loading').show();
    if($('#free-shipping-switch-revise').prop('checked')){
      var freeShippingRevise = [true,$('#shipping-serivce').val()];
    }else{
      var freeShippingRevise = [$('#shipping-cost-one-revise').val(),$('#shipping-cost-addition-revise').val(),$('#shipping-serivce').val()];
    }
    if($('#express-shipping-switch-revise').prop('checked')){
      var expressShipping = [$('#shipping-express-cost-one-revise').val(),$('#shipping-express-cost-addition-revise').val()];
    }else{
      var expressShipping = false;
    }
    $.ajax({
      url: '/listing/revise-one-item',
      type: 'POST',
      //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        ebayID: $('#ebay-id-revise').val(),
        itemID:itemID,
        title:$('#item-title-revise').val(),
        price:$('#item-price-revise').val(),
        qty:$('#item-qty-revise').val(),
        shipping:freeShippingRevise,
        expressShipping:expressShipping
      }
    })
    .done(function(result) {
      $('#revise-loading').hide();
      $('#revise-listing-modal .notice-board .message').html(result.message);
      if(result.status==="success"){
        $('#revise-listing-modal .notice-board .alert').switchClass('alert-danger','alert-success').fadeIn();
      }else{
        $('#revise-listing-modal .notice-board .alert').switchClass('alert-success','alert-danger').fadeIn();
      }
      $('#revise-listing-modal .notice-board').fadeIn();
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  });
  $('#btn-add-listing-submit').click(function(){
    $('#listing-details').hide();
    $('#similar-search-loading').show();
    // if($('#best-offer-switch').prop('checked')){
    //   var bestOffer = [$('#accept-price').val(),$('#decline-price').val()];
    // }else{
    //   var bestOffer = false;
    // }
    // if($('#free-shipping-switch').prop('checked')){
    //   var freeShipping = true;
    // }else{
    //   var freeShipping = [$('#shipping-cost-one').val(),$('#shipping-cost-addition').val()];
    // }
    var bestOfferShipping = bestOfferShippingSwitch();
    var postData = buildPostData(true,bestOfferShipping);
    $.ajax({
      url: '/listing/add-fixed-price-listing',
      type: 'POST',
      //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: postData

    })
    .done(function(result) {
      $('#similar-search-loading').hide();

      $('#add-listing-modal .notice-board .message').html(result.message);
      if(result.status==="success"){
        $('#add-listing-modal .notice-board .alert').switchClass('alert-danger','alert-success').fadeIn();
      }else{
        $('#add-listing-modal .notice-board .alert').switchClass('alert-success','alert-danger').fadeIn();
      }
      $('#add-listing-modal .notice-board').fadeIn();
      console.log(result);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  });

  $('#best-offer-switch').change(function(){
    if($(this).prop('checked')){
      $('#best-offer-price').show();
    }else{
      $('#best-offer-price').hide();
    }
  });
  $('#free-shipping-switch').change(function(){
    if($(this).prop('checked')){
      $('#shipping-cost').hide();
    }else{
      $('#shipping-cost').show();
    }
  });
  $('#free-shipping-switch-revise').change(function(){
    if($(this).prop('checked')){
      $('#shipping-cost-revise').hide();
    }else{
      $('#shipping-cost-revise').show();
    }
  });
  $('#express-shipping-switch-revise').change(function(){
    if($(this).prop('checked')){
      $('#shipping-cost-express-revise').show();
    }else{
      $('#shipping-cost-express-revise').hide();
    }
  });
});
function buildPostData(verify,bestOfferShipping){
  return {
    verify:verify,
    ebayID: $('#ebay-id').val(),
    productID:$('#product-id').val(),
    ebayCatID:$('#item-cate-id').val(),
    itemTitle:$('#item-title').val(),
    itemPrice:$('#item-price').val(),
    qty:$('#item-qty').val(),
    shippingService:$('#shipping-serivce').val(),
    bestOffer:bestOfferShipping[0],
    freeShipping:bestOfferShipping[1]
  };
}
function bestOfferShippingSwitch()
{
  if($('#best-offer-switch').prop('checked')){
    var bestOffer = [$('#accept-price').val(),$('#decline-price').val()];
  }else{
    var bestOffer = false;
  }
  if($('#free-shipping-switch').prop('checked')){
    var freeShipping = true;
  }else{
    var freeShipping = [$('#shipping-cost-one').val(),$('#shipping-cost-addition').val()];
  }

  return [bestOffer,freeShipping];
}
function initCreateModal(e){
  $('#listing-details').show();
  $('#add-listing-modal .notice-board').hide();
  $('#add-listing-modal .notice-board .message').html('');
  $('#add-listing-modal .notice-board .alert').removeClass('alert-danger').removeClass('alert-success').fadeIn();
}
function initReviseModal(e){
  $('#revise-listing-modal .notice-board').hide();
  $('#revise-listing-modal .notice-board .message').html('');
  $('#revise-listing-modal .notice-board .alert').removeClass('alert-danger').removeClass('alert-success').fadeIn();
  $('#revise-form').hide();
  $('#revise-loading').show();
  itemID = $(e.relatedTarget).data('item-id')
  $.ajax({
    url: '/listing/get-one-item-revise-info',
    type: 'POST',
    //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
    data: {itemID: itemID,ebayID: $('#ebay-id-revise').val()}
  })
  .done(function(item) {
    if(item!==false){
      if(item.shippingServiceOptions[1]!==undefined){
        $('#express-shipping-switch-revise').prop('checked', true);
        $('#shipping-cost-express-revise').show();
        $('#shipping-express-cost-one-revise').val(item.shippingServiceOptions[1].shippingCost);
        $('#shipping-express-cost-addition-revise').val(item.shippingServiceOptions[1].shippingAddCost);
      }
      $('#shipping-serivce').val(item.shippingServiceOptions[0].shippingService);
      $('#shipping-serivce option[value='+item.shippingServiceOptions[0].shippingService+']').prop('selected',true);
      $('#item-title-revise').val(item.title);
      $('#item-price-revise').val(item.price);
      $('#item-qty-revise').val(item.qty);
      if(item.shippingServiceOptions[0].shippingCost==0){
        $('#free-shipping-switch-revise').prop('checked', true);
        $('#shipping-cost-one-revise').val('');
        $('#shipping-cost-addition-revise').val('');
        $('#shipping-cost-revise').hide();
      }else{
        $('#shipping-cost-revise').show();
        $('#free-shipping-switch-revise').prop('checked', false);
        $('#shipping-cost-one-revise').val(item.shippingServiceOptions[0].shippingCost);
        $('#shipping-cost-addition-revise').val(item.shippingServiceOptions[0].shippingAddCost);
      }
      $('#revise-form').show();
      $('#revise-loading').hide();
    }else{
      alert('Error!please contact admin');
    }

  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });

  $('#revise-listing-modal .notice-board').hide();
  $('#revise-listing-modal .notice-board .message').html('');
  $('#revise-listing-modal .notice-board .alert').removeClass('alert-danger').removeClass('alert-success').fadeIn();
}
function showSellerId(e){

  $($(e.relatedTarget).data('target')).find('#ebay-id').val($(e.relatedTarget).data('ebay-id'));
  $($(e.relatedTarget).data('target')).find('#ebay-id-revise').val($(e.relatedTarget).data('ebay-id'));
  $($(e.relatedTarget).data('target')).find('#ebay-seller-id').html($(e.relatedTarget).data('ebay-seller'));
  $($(e.relatedTarget).data('target')).find('#item-id-fill').html($(e.relatedTarget).data('item-id'));
}
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
		//console.log(newSort);
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
		//console.log(result);
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
	//progressBarInit = 8;
}

function pjaxReload(ebayID){
  $.pjax.reload({container: '#listing-images-pjax-'+ebayID,timeout:5000});
}
