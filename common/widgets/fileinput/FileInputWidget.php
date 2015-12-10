<?php 
namespace common\widgets\fileinput;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class FileInputWidget extends \yii\widgets\InputWidget
{
	
	public $options = [];

	public $clientOptions = [];

	public function init()
    {
        FileInputAsset::register($this->getView());
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {	
    	echo $this->hasModel()
    	? Html::activeInput('file',$this->model,$this->attribute, $this->options)
    	: Html::input('file',$this->name, $this->value, $this->options);
    	$clientOptions = empty($this->clientOptions)
            ? null
            : Json::encode($this->clientOptions);
        $this->getView()->registerJs('jQuery( "#' . $this->options['id'] . '" ).fileinput(' . $clientOptions . ');');
    }
   
}
?>
