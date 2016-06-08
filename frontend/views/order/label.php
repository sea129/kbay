<?php
use yii\helpers\Url;
?>

<!DOCTYPE html>
<html>
<body>
  <?php if($orderIndex!=0){
    ?>
    <pagebreak>
  <?php } ?>

<table cellpadding='0' border='0' cellspacing='0' width='100%' height='100%' style="color:#2b2b2b;font-family:Helvetica;font-size:14px;">
  <tr>
    <td>
      <table cellpadding='0' border='0' cellspacing='0' width='100%' height='100%'>
        <tr>
          <td width="200" valign="top" align="left">
            <table cellpadding='0' border='0' cellspacing='0' width='100%' height='100%' style="border:none;">
              <tr>
                <td width='100'>
                  To
                </td>
                <td>
                  PH: <?php echo $order['recipient_phone']; ?>
                </td>
              </tr>
              <tr>
                <td height='5'>

                </td>
              </tr>
              <tr>
                <td colspan='2' style="font-size:15x;">
                  <?php echo $order['recipient_name']; ?>
                </td>
              </tr>
            </table>
          </td>


          <td width="30" valign="top" align="right">
            <barcode code="<?php echo $order['ebay_order_id']; ?>" type="QR" size="0.6" style='border:none' />
          </td>
        </tr>
        <tr>
          <td valign="top">
            <?php echo $order['recipient_address1'] ?>
          </td>
        </tr>
        <tr>
          <td height='5'>

          </td>
        </tr>
        <tr>
          <td height='20' valign='top'>
            <?php echo $order['recipient_address2'] ?>
          </td>
        </tr>
        <tr>
          <td valign='bottom'>
            <?php echo $order['recipient_city'].'&nbsp;'.$order['recipient_state'].'&nbsp;'.$order['recipient_postcode']; ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height='20' style='font-size:10px;' align='center' valign='middle'>
      <?php //echo $packSign.' / '.$weight.' / '; ?>
    </td>
  </tr>
<?php echo $transLabel; ?>
</table>
<div style='position:absolute;bottom:15px;width:100%;'>
<table cellpadding='0' border='0' cellspacing='0' width='100%' height='100%' style="color:#2b2b2b;font-family:Helvetica;font-size:12px;">
  <tr>
    <td>
      <table cellpadding='0' border='0' cellspacing='0' width='100%' height='100%' style="color:#2b2b2b;font-family:Helvetica;font-size:12px;">
        <tr>
          <td width='45%'>
            <?= $order['ebay_seller_id'] ?>
          </td>
          <td width='45%'>
            <?php echo $order['buyer_id']; ?>
          </td>
          <td width='10%'>
            <?php echo $packSign['buyerCount'] ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table cellpadding='0' border='0' cellspacing='0'  height='100%' style="color:#2b2b2b;font-family:Helvetica;font-size:12px;table-layout:fixed;width:375px;">
        <tr>
          <td width='40' style='font-size:16px;font-weight:bold;'>
            <?php echo $packSign['fastway']?'F':''; ?>
          </td>
          <td width='40' style='font-size:16px;font-weight:bold;'>
            <?php echo $packSign['eParcel']?'eP':''; ?>
          </td>
          <td width='40' style='font-size:16px;font-weight:bold;'>
            <?php echo $packSign['express']?'EX':''; ?>
          </td>
          <td width='40' style='font-size:16px;font-weight:bold;'>
            <?php echo $packSign['checkoutMessage']?'M':''; ?>
          </td>
          <td width='107'>
            <?php echo $packSign['weight'].'Kg'; ?>
          </td>
          <td width='108'>
            <?php echo '$'.$packSign['totalCost']; ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</div>

<?php if($orderIndex!=0){
  ?>
</pagebreak>
<?php } ?>
</body>
</html>
