$(function(){

});
function getDownloadListObj(){
  return $('.download-orders-container ul');
}
function getUpdateNotPaidListObj(){
  return $('.update-not-paid-order-container ul');
}

function downloadOrders(ebayID, pageNum)
{
  getDownloadListObj().append("<li><i class='fa fa-refresh fa-spin fa-fw fa-li fa-lg'></i>下载第"+pageNum+"页...</li>");
  $.ajax({
    url: 'download',
    type: 'POST',
    data: {ebayID:ebayID,pageNum}
  })
  .done(function(result) {
    if(result.Error){
      getDownloadListObj().children().last().children('i').removeClass('fa-refresh fa-spin').addClass('fa-exclamation-triangle red');
      $.each(result.Error,function(index,value){
        getDownloadListObj().append("<li style='color:red'><i class='fa fa-times fa-fw fa-li fa-lg'></i>"+value+"</li>");
      });
      getDownloadListObj().children().first().children('i').removeClass('fa-refresh fa-spin').addClass('fa-exclamation-triangle red');
      $('.download-orders-container').ace_scroll({size:500});
    }else{
      getDownloadListObj().children().last().children('i').removeClass('fa-refresh fa-spin').addClass('fa-check green');
      if(result.moreOrders == true){
        pageNum++;
        downloadOrders(ebayID, pageNum);
      }else{
        getDownloadListObj().children().first().children('i').removeClass('fa-refresh fa-spin').addClass('fa-check green');
        getDownloadListObj().append("<li><i class='fa fa-check green fa-fw fa-li fa-lg'></i>"+result.orderCounts+"个订单下载成功</li>");
        $('.download-orders-container').ace_scroll({size:500});
      }
    }
    //console.log(result);
  })
  .fail(function() {
    //console.log("error");
  })
  .always(function() {
    //console.log("complete");
  });
}
function downloadOrdersInit(e){
  var ebayID = $(e.relatedTarget).data('ebayId');
  downloadOrders(ebayID,1);
}

function updateNotPaidOrders(ebayID, pageNum){
  getUpdateNotPaidListObj().append("<li><i class='fa fa-refresh fa-spin fa-fw fa-li fa-lg'></i>更新第"+pageNum+"页...</li>");

  $.ajax({
    url: 'update-not-paid',
    type: 'POST',
    data: {ebayID: ebayID, pageNum:pageNum}
  })
  .done(function(result) {
    if(result.Error){
      getUpdateNotPaidListObj().children().last().children('i').removeClass('fa-refresh fa-spin').addClass('fa-exclamation-triangle red');
      $.each(result.Error,function(index,value){
        getUpdateNotPaidListObj().append("<li style='color:red'><i class='fa fa-times fa-fw fa-li fa-lg'></i>"+value+"</li>");
      });
      getUpdateNotPaidListObj().children().first().children('i').removeClass('fa-refresh fa-spin').addClass('fa-exclamation-triangle red');
      $('.download-orders-container').ace_scroll({size:500});
    }else{
      getUpdateNotPaidListObj().children().last().children('i').removeClass('fa-refresh fa-spin').addClass('fa-check green');
      if(result.moreOrders == true){
        pageNum++;
        updateNotPaidOrders(ebayID, pageNum);
      }else{
        getUpdateNotPaidListObj().children().first().children('i').removeClass('fa-refresh fa-spin').addClass('fa-check green');
        getUpdateNotPaidListObj().append("<li><i class='fa fa-check green fa-fw fa-li fa-lg'></i>未付款订单更新成功</li>");
        $('.download-orders-container').ace_scroll({size:500});
      }
    }
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });

}
function updateNotPaidOrdersInit(e){
  var ebayID = $(e.relatedTarget).data('ebayId');
  updateNotPaidOrders(ebayID,1);
}
