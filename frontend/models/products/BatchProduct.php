<?php 
namespace frontend\models\products;

use yii\web\NotFoundHttpException;
/**
*
*/
class BatchProduct extends Product
{
	
	public function __construct($id=null)
	{
		parent::__construct();
		if($id==null){
			//throw new NotFoundHttpException('NOT valid main product.');
		}else{
			$mainProduct = Product::findOne($id);
			if(($mainProduct->stock_qty==null) ||($mainProduct->qty_per_order!=1)){
	            throw new NotFoundHttpException('NOT valid main product.');
	        }else{
	           //$this->setAttributes($mainProduct->getAttributes());
	           $this->scenario = 'add';
	           $this->attributes = $mainProduct->getAttributes();
	           $this->qty_per_order = 2;
	           $this->stock_qty = null;

	        }
		}
	}

	public function rules()
	{
		

		return array_merge(parent::rules(),[
			['qty_per_order','compare','compareValue' => 1, 'operator'=>'>'],
		]);
	}

	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios['add'] = ['sku','mini_desc', 'name', 'weight', 'description', 'specs', 'comment','supplier_id', 'packaging_id', 'is_trackable','stock_location','cost','category_id','qty_per_order','main_image'];
		return $scenarios;
	}

}
 ?>