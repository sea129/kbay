$(function(){

  fetchInit();
  $('#btn-main-fetch').click(function(){
    $(this).addClass('disabled').prop('disabled', true);
    fetch(1, ebayID, totalPages);
    $('#fetch-progress-bar').fadeIn();
  });

  // $('#batch-label').click(function(){
  //   //var keys = $('#main-grid').yiiGridView('getSelectedRows');
  //   var selection = $('.check-selection:checked').map(function(){
  //     return $(this).val();
  //   }).get();
  //   ajax
  // });

});

function fetchInit(){
  ebayID = false;
  OrderCount = 0;
  totalPages = 0;
  saveLog = true;
  savingError = [];
  $('#pre-fetch-loading').show();
  $('#btn-main-fetch').removeClass('disabled').prop('disabled', false).hide();
  $('#no-orders').html('');
  $('#fetch-progress-bar').hide();
  $('#fetch-progress-bar .progress-bar').width('0%').html('0%');
}

function closeSyncModal(e){
  fetchInit();
  $('.notice-board').hide();
  $('.notice-board .message').html('');
  $('.notice-board .alert').removeClass('alert-danger').fadeOut();
  $('.notice-board .alert').removeClass('alert-success').fadeOut();
  $('#saving-error').hide();
  $('#saving-error ul').html('');
  $('#saving-error p').html('Completed!');
  location.reload();
}

function preFetchModal(e){
  ebayID = $(e.relatedTarget).data('ebayId');
  createTo = $(e.relatedTarget).data('createTo');
  $.ajax({
    url: 'pre-fetch',
    type: 'POST',
    data: {ebayID:ebayID}
  })
  .done(function(result) {
    if(result.status==='success'){
      $('.notice-board .message').html(result.message);
      $('.notice-board .alert').toggleClass('alert-success').fadeIn();
      $('.notice-board').fadeIn();
      $('#pre-fetch-loading').hide();
      $('#no-orders').html(result.fetchQtyCount);
      totalPages = result.totalPages;
      $('#btn-main-fetch').fadeIn();
    }else{
      if(typeof result.savingError !== 'undefined'){
        $.each(result.savingError,function(index,errors){
          $.each(errors,function(index,error){
            result.message+=" - "+error[0];
          });
        });
      }
      $('#pre-fetch-loading').hide();
      $('#no-orders').html('Error!');
      $('.notice-board .message').html(result.message);
      $('.notice-board .alert').toggleClass('alert-danger').fadeIn();
      $('.notice-board').fadeIn();
    }



    //console.log(result);
    // if(result.status==='success'){
    //
    // }else{
    //
    // }
    // //console.log("pre sync ajax Status: "+result.status,"pre sync ajax message: "+result.message);
  })
  .fail(function() {
    console.log("error01 ajax fail");
  })
  .always(function() {
    //console.log("complete");
  });
}
function progressBar(width){
  //var curWidth = $('#sync-progress-bar .progress-bar').width();
  $('#fetch-progress-bar .progress-bar').width(width+'%').html(width+'%');
}
function fetch(pageNumber,ebayID,totalPages){
  if(pageNumber<=totalPages){
    $.ajax({
      url: 'main-fetch',
      type: 'POST',
      data: {ebayID:ebayID,pageNumber:pageNumber}
    })
    .done(function(result) {
      if(result.status==='success'){

        progressBar(Math.floor((pageNumber/totalPages)*100));
        pageNumber++;
        if(typeof result.savingError !== 'undefined'){

          $.each(result.savingError,function(index,errors){
            //console.log(errors.ebay_order_id);

            if(errors.ebay_order_id === undefined){
              saveLog = false;
            }
            $.each(errors,function(index,error){
              savingError.push(error[0]);
            });
          });

        }
        fetch(pageNumber,ebayID,totalPages);
        //return true;
      }else{


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
    if(saveLog){
      $.ajax({
        url: 'save-log',
        type: 'POST',
        data: {ebayID:ebayID}
      })
      .done(function(result) {
        if(result){
          progressBar(100);
          $('#saving-error p').append('<br/>order log status update to DONE!');
        }else{
          $('#saving-error p').append('<br/>order log status ERROR while updating!');
        }
      })
      .fail(function() {
        //console.log("error");
      })
      .always(function() {
        //console.log("complete");
      });

    }
    if(savingError.length>0){
      $.each(savingError,function(index,error){
        $('#saving-error ul').append("<li><i class='ace-icon fa fa-times bigger-110 red'></i>"+error+"</li>");
      });
      $('#error-scroll').ace_scroll({size:125});

    }
    $('#saving-error').show();
  }
}
