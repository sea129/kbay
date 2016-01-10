<?php

namespace frontend\models\products;

use Yii;

use yii\web\NotFoundHttpException;
use frontend\models\ebayaccounts\EbayAccount;
use frontend\models\category\Category;
use frontend\models\stocklocation\StockLocation;
use frontend\models\supplier\Supplier;
use yii\web\UploadedFile;
use frontend\models\packagingpost\PackagingPost;
use frontend\models\listingimages\ListingImages;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $sku
 * @property string $name
 * @property string $mini_desc
 * @property integer $stock_qty
 * @property string $cost
 * @property string $description
 * @property string $specs
 * @property integer $category_id
 * @property integer $user_id
 * @property string $stock_location
 * @property integer $supplier_id
 * @property integer $packaging_id
 * @property integer $weight
 * @property integer $is_trackable
 * @property string $comment
 * @property integer $qty_per_order
 *
 * @property Category $category
 * @property StockLocation $stockLocation
 * @property Packaging $packaging
 * @property Supplier $supplier
 * @property User $user
 * @property ProductEbayListing[] $productEbayListings
 */
class Product extends \frontend\models\base\MyActiveRecord
{

    const SCENARIO_BATCH = 'batch';
    const SCENARIO_SINGLE = 'single';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    public function init(){
        parent::init();
        if($this->user_id===null){
            $this->user_id = Yii::$app->user->id;
        }elseif($this->user_id!=Yii::$app->user->id){

            throw new NotFoundHttpException('The product belong to another user');

        }
        //$this->user_id = Yii::$app->user->id;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku', 'user_id', 'supplier_id', 'packaging_id', 'is_trackable', 'qty_per_order','stock_location','weight','cost','name','main_image'], 'required'],
            [['mini_desc', 'description', 'specs', 'comment'], 'string'],
            [['category_id', 'user_id', 'supplier_id', 'packaging_id', 'weight', 'is_trackable', 'qty_per_order'], 'integer'],
            [['cost'], 'number'],
            [['sku', 'stock_location'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 128],
            [['category_id'],'exist','targetClass'=>Category::className(),'targetAttribute'=>'id','message' => Yii::t('app/category', 'The category does not exist')],
            [['supplier_id'],'exist','targetClass'=>Supplier::className(),'targetAttribute'=>'id','message' => Yii::t('app/supplier', 'The Supplier does not exist')],
            [['stock_location'],'exist','targetClass'=>StockLocation::className(),'targetAttribute'=>'code','message' => Yii::t('app/supplier', 'The StockLocation does not exist')],
            [['sku', 'user_id'], 'unique', 'targetAttribute' => ['sku', 'user_id'], 'message' => 'SKU has already been taken.'],
            [['name', 'user_id'], 'unique', 'targetAttribute' => ['name', 'user_id'], 'message' => 'Name has already been taken.'],
            ['qty_per_order','compare','compareValue'=>1,'operator'=>'==','on'=>self::SCENARIO_SINGLE],
            ['qty_per_order','compare','compareValue' => 1, 'operator'=>'>','on'=>self::SCENARIO_BATCH],
            ['stock_qty','integer','on'=>self::SCENARIO_SINGLE],
            ['stock_qty','compare','compareValue' => null, 'operator'=>'==','on'=>self::SCENARIO_BATCH],

        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/product', 'ID'),
            'sku' => Yii::t('app/product', 'Sku'),
            'name' => Yii::t('app/product', 'Name'),
            'mini_desc' => Yii::t('app/product', 'Mini Desc'),
            'stock_qty' => Yii::t('app/product', 'Stock Qty'),
            'cost' => Yii::t('app/product', 'Cost'),
            'description' => Yii::t('app/product', 'Description'),
            'specs' => Yii::t('app/product', 'Specs'),
            'category_id' => Yii::t('app/product', 'Category ID'),
            'user_id' => Yii::t('app/product', 'User ID'),
            'stock_location' => Yii::t('app/product', 'Stock Location'),
            'supplier_id' => Yii::t('app/product', 'Supplier ID'),
            'packaging_id' => Yii::t('app/product', 'Packaging ID'),
            'weight' => Yii::t('app/product', 'Weight'),
            'is_trackable' => Yii::t('app/product', 'Is Trackable'),
            'comment' => Yii::t('app/product', 'Comment'),
            'qty_per_order' => Yii::t('app/product', 'Qty Per Order'),

            'main_image' => Yii::t('app/product', 'Product Image'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockLocation()
    {
        return $this->hasOne(StockLocation::className(), ['code' => 'stock_location']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagingPost()
    {
        return $this->hasOne(PackagingPost::className(), ['id' => 'packaging_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getProductRelations()
   {
       return $this->hasMany(ProductRelation::className(), ['main' => 'id']);
   }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getProductRelations0()
   {
       return $this->hasMany(ProductRelation::className(), ['sub' => 'id']);
   }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductEbayListings()
    {
        return $this->hasMany(ProductEbayListing::className(), ['sku' => 'sku']);
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

        /**
     * 拿到所有属于当前用户的ebay账户, 为了在product/index里用
     * @return ['ebay ID'=>'seller ID',....]
     */
    public function getEbayAccouts()
    {
        $ebayAccArray = EbayAccount::find()->allOfUser(Yii::$app->user->id,$db = null);

        $ebayAccArrayP = [];
        foreach ($ebayAccArray as $value) {
            $ebayAccArrayP[$value['id']] = $value['seller_id'];
        }

        return $ebayAccArrayP;
    }

    public function getEbayAccoutsObj()
    {
        $ebayAcc = EbayAccount::find()->allOfUserObj(Yii::$app->user->id,$db = null);

        return $ebayAcc;
    }

    private function getDropdownArray($model,$key,$label){
        $objArray = $model::find()->allOfUser(Yii::$app->user->id,$db = null);

        $dropdownArray = [];
        foreach ($objArray as $value) {
            $dropdownArray[$value[$key]] = $value[$label];
        }

        return $dropdownArray;
    }


    public function getCategories()
    {
      $objArray = \frontend\models\category\Category::find()->allOfUser(Yii::$app->user->id,$db = null);
      $dropdownArray = [];
      $optionArray=[];
      foreach ($objArray as $value) {
          $dropdownArray[$value['id']] = $value['name'];
          $optionArray[$value['id']]=['data-cat-code'=>$value['code']];
      }
      return ['dropdown'=>$dropdownArray,'option'=>$optionArray];
    }

    public function getStockLocations()
    {
        return $this->getDropdownArray('frontend\models\stocklocation\StockLocation','code','code');
    }
    public function getSuppliers()
    {
        return $this->getDropdownArray('frontend\models\supplier\Supplier','id','name');
    }
    public function getAllPackagings()
    {
        return $this->getDropdownArray('frontend\models\packagingpost\PackagingPost','id','name');
    }


    public function uploadImage()
    {
        $image = UploadedFile::getInstance($this, 'main_image');

        if(empty($image)){
            return false;
        }

        return $image;
    }

    /**
     * 上传处理,保存listing template image到临时文件夹.
     * @return [type] [description]
     */
    public function uploadTmpLstImg($ebaySeller){
        if($this->validate()){
            $folder = Yii::$app->params['privateImagePath'].'listing-images/'.$this->user_id.'/'.$ebaySeller.'/'.$this->id.'/';
            if(!is_dir($folder)){
                mkdir($folder,0755,true);
            }
            $this->listingTmpImage->saveAs($folder. substr(str_shuffle(MD5(microtime())), 0, 8). '.' . $this->listingTmpImage->extension);
            return true;
        }
    }

    public function getEbayLstImgs(){
      return $ebayLstImgs = ListingImages::find()->allOfProduct($this->id);
    }

    public function formSortableItems($listingImages){
      $result = [];
      foreach ($listingImages as $ebayID => $images) {
        $url = json_decode($images['image_url']);
        foreach ($url as $key => $value) {
           $result[$ebayID][] = [
             'content'=>'<div class="lst-img-container"><img src='.$value.' draggable="false" class="countable"></div><div class="lst-img-delete-container"><a class="red btn-i-delete-images"><i class="ace-icon fa fa-trash-o bigger-110" ></i></a></div>',
             //'content'=>'<div class="lst-img-container"><img src='.$value.'></div><div class="lst-img-delete-container"><a href="#" class="red btn-i-delete-images"><i class="ace-icon fa fa-trash-o bigger-110" ></i></a></div>',
             //'options'=>['id'=>'img_'.$ebayID.'_'.$key],
           ];
        }

      }
      return $result;
    }
}
