<tr>
  <td>
    <table cellpadding='0' border='0' cellspacing='0' width='100%' height='100%' style="border:none;font-size:11px;">
      <tr>
        <td width='10%' align='left' style='font-size:13px;'>
          <?php echo $transaction['qty_purchased']; ?>
        </td>
        <td>
          <?php echo $transaction['variation']?$transaction['variation']:$transaction['item_title']; ?>
        </td>
      </tr>
    </table>
  </td>
</tr>
