<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<body>
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
    <td height='40' style='font-size:10px;' align='center' valign='middle'>
      <?php echo $packSign.' / '.$weight.' / '; ?>
    </td>
  </tr>
<?php echo $transLabel; ?>
</table>
<pagebreak />


</body>
</html>
