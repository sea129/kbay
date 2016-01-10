$(function(){
  fetchInit();
  $('#btn-main-fetch').click(function(){
    $(this).addClass('disabled');
    fetch(1, ebayID, totalPages);
    $('#fetch-progress-bar').fadeIn();
  });

  $('#test-time').click(function(){
    $.ajax({
      url: 'time',
      type: 'post',
      data: {ebayID:3}
    })
    .done(function(result) {
      console.log(result);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  });
});

function fetchInit(){
  ebayID = false;
  OrderCount = 0;
  totalPages = 0;
  buyerID = [];
  $('#pre-fetch-loading').show();
  $('#btn-main-fetch').removeClass('disabled');
}

function closeSyncModal(e){
  fetchInit();
  $('.notice-board').hide();
  $('.notice-board .message').html('');
  $('.notice-board .alert').toggleClass('alert-danger').fadeOut();
  $('.notice-board .alert').toggleClass('alert-success').fadeOut();
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
        console.log(result.buyerID);
        progressBar(Math.floor((pageNumber/totalPages)*100));
        pageNumber++;

        fetch(pageNumber,ebayID,totalPages);
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
    console.log(buyerID);
  }
}
