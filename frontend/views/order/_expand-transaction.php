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
           Paid Time
         </td>
         <td>
           eBay TransactionID
         </td>
       </tr>
     <?php foreach ($transactions as $transaction) { ?>

       <tr>
         <td class="center">
           <?php echo $transaction->sale_record_number; ?>
         </td>
         <td class="center">
           <img src="<?php echo $transaction->image; ?>" alt="" />
         </td>
         <td class="">
           <?php echo $transaction->item_id; ?><br>
           <?php echo $transaction->item_sku; ?><br>
           <?php echo $transaction->item_title; ?>
         </td>
         <td class="right">
           <?php echo $transaction->qty_purchased; ?>
         </td>
         <td class="right">
           <?php echo $transaction->transaction_price; ?>
         </td>
         <td>
           <?php echo $transaction->paid_time; ?>
         </td>
         <td>
           <?php echo $transaction->transaction_id; ?>
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
