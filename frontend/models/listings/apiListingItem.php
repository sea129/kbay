<?php
namespace frontend\models\listings;

use Yii;

class ApiListingItem extends Listing
{
  public $description;

  public $bestOffer = false;

  public $shippingCost = true; //true for free shipping

  public $shippingExpressCost = false;
  
  public $location;

  public $picture;

  public $primaryCate;

  public $shippingService;

  public $paypal;
}
 ?>
