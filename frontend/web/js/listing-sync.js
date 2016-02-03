$(function(){
  syncInit();
  $('#btn-main-sync').click(function(){
    Sync(ebayID);
    $(this).addClass('disabled').prop('disabled', true);
    $('#sync-progress-bar').fadeIn();
  });
});

function syncInit()
{
  //listingCount = 0;
  totalPages = 0;
  ebayID = false;
  syncListings = [];
  noSKUListings = [];
  $('#btn-main-sync').removeClass('disabled').prop('disabled', false);
}
function closeSyncModal(e){
  syncInit();
  $('#pre-sync-loading').show();
  $('#no-listings').html('');
  $('#btn-main-sync').hide();
  $('.sync-result').hide();
  $('#no-success-sync').html('');
  $('#no-empty-sku').html('');
  $('#sync-progress-bar').hide();
  $('#sync-progress-bar .progress-bar').width('0%').html('0%');
  $('.notice-board').hide();
  $('.notice-board .message').html('');
  $('.notice-board .alert').removeClass('alert-danger').fadeIn();
  $('#empty-sku-list').hide();
  $('#empty-sku-list ul').html('');
}
function preSyncModal(e){
  ebayID = $(e.relatedTarget).data('ebayId');//first time define ebay account ID
  //console.log('Ebay ID '+ebayID);
  $.ajax({
    url: 'pre-sync',
    type: 'POST',
    data: {ebayID:ebayID}
  })
  .done(function(result) {
    if(result.status==='success'){
      $('#pre-sync-loading').hide();
      $('#no-listings').html(result.data);
      //listingCount = result.data;
      totalPages = result.totalPages;
      $('#btn-main-sync').fadeIn();
      //$('.notice-board .message').html(result.message);
      //$('.notice-board .alert').toggleClass('alert-success').fadeIn();
      //$('.notice-board').fadeIn();

    }else{
      $('#pre-sync-loading').hide();
      $('#no-listings').html('Error!');
      $('.notice-board .message').html(result.message);
      $('.notice-board .alert').toggleClass('alert-danger').fadeIn();
      $('.notice-board').fadeIn();
    }
    //console.log("pre sync ajax Status: "+result.status,"pre sync ajax message: "+result.message);
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    //console.log("complete");
  });


}

function Sync(ebayID)
{
  syncAjax(1, ebayID, totalPages);
}
function progressBar(width){
  //var curWidth = $('#sync-progress-bar .progress-bar').width();
  $('#sync-progress-bar .progress-bar').width(width+'%').html(width+'%');
}
function syncAjax(pageNumber,ebayID,totalPages){
  if(pageNumber<=totalPages){
    $.ajax({
      url: 'sync-page',
      type: 'POST',
      data: {ebayID:ebayID,pageNumber:pageNumber}
    })
    .done(function(result) {
      if(result.status==='success'){
        console.log(pageNumber);
        syncListings=$.merge(syncListings,result.listings_sync);
        noSKUListings=$.merge(noSKUListings,result.listings_nosku);

        progressBar(Math.floor((pageNumber/totalPages)*100));
        pageNumber++;

        syncAjax(pageNumber,ebayID,totalPages);

        //return true;
      }else{
        if(typeof result.savingError !== 'undefined'){
          $.each(result.savingError,function(index,errors){
            $.each(errors,function(index,error){
              result.message+=" - "+error[0];
            });
          });
        }

        $('.notice-board .message').html(result.message);
        $('.notice-board .alert').toggleClass('alert-danger').fadeIn();
        $('.notice-board').fadeIn();

        //console.log(result.savingError);
      }
      //console.log("success");
    })
    .fail(function() {
      console.log("ajax fail");
    })
    .always(function() {
      //console.log("complete");
    });
  }else{
    //console.log(noSKUListings);
    //console.log(syncListings);
    $('#no-success-sync').html(syncListings.length);
    $('#no-empty-sku').html(noSKUListings.length);
    if(noSKUListings.length>0){
      $.each(noSKUListings,function(index,listingID){
        $('#empty-sku-list ul').append("<li><i class='ace-icon fa fa-times bigger-110 red'>"+listingID+"</i></li>");
      });
      $('#sku-scroll').ace_scroll({size:125});
      $('#empty-sku-list').show();
    }
    $('.sync-result').show();
  }


}
