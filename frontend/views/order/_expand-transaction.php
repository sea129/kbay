<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
  // echo "<pre>";
  // echo var_dump($model->ebayTransactions);
  // echo "</pre>";
   $transactions = $model->ebayTransactions;
  // $dataProvider = new ActiveDataProvider([
  //     'query' => $model->getEbayTransactions(),
  //     'pagination'=>[
  //       'pageSize' => 20,
  //     ],
  //     'sort' => [
  //       'defaultOrder' => [
  //           'item_title' => SORT_DESC,
  //           'paid_time' => SORT_DESC,
  //       ]
  //   ],
  // ]);
 ?>
 <table class="table table-striped table-bordered table-hover">
   <tbody>
       <tr>
         <td class="center">
           Record No.
         </td>
         <td class="center">
           Image
         </td>
         <td class="">
           Item
         </td>
         <td class="">
           Quantity
         </td>
         <td class="">
           Total
         </td>
         <td>
           eBay TransactionID
         </td>
         <td>
           Tracking
         </td>
       </tr>
     <?php foreach ($transactions as $transaction) { ?>

       <tr>
         <td class="center">
           <?php echo $transaction->sale_record_number; ?>
         </td>
         <td class="center">
           <?php if(isset($transaction->image)){ ?>
              <img src="<?php echo $transaction->image; ?>" alt="" style="max-width:150px;max-height:150px;"/>
           <?php  }else{ ?>
             <button class="btn btn-danger get_image" data-transactionid="<?php echo $transaction->transaction_id; ?>" data-item="<?php echo $transaction->item_id ?>" data-ebayid="<?php echo $model->ebay_id ?>" ><i class="ace-icon fa fa-refresh  bigger-110 icon-only"></i></button>
          <?php } ?>
           <!-- <img src="<?php //echo $model->getItemPicUrl($transaction->item_id); ?>" alt="" style="max-width:150px;max-height:150px;"/> -->
         </td>
         <td class="">
           <?php echo $transaction->item_id; ?><br><br>
           <?php echo $transaction->item_sku; ?><br><br>
           <?php echo $transaction->item_title; ?><br><br>
           <span style='color:red'><?php echo $model->checkout_message; ?></span>
         </td>
         <td class="right">
           <?php echo $transaction->qty_purchased; ?>
         </td>
         <td class="right">
           <?php echo $transaction->transaction_price; ?>
         </td>
         <td>
           <?php echo $transaction->transaction_id; ?>
         </td>
         <td>
           <?php echo $transaction->tracking_number; ?><br>
           <?php echo $transaction->shipping_carrier; ?>
         </td>
       </tr>

    <?php } ?>

   </tbody>
 </table>
<?php
// echo GridView::widget([
//     'dataProvider' => $dataProvider,
//     'hover'=>true,
//   ]);

?>
