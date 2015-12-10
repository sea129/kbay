<?php 

echo $this->renderFile('@app/views/product/templates/tpl_ozsuperdeal.php',[
            'ebayAcc'=>$ebayAcc,
            'product'=>$product,
        ]);
 ?>