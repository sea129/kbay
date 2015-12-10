<?php 
namespace common\widgets\summernote;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class SummernoteWidget extends \yii\widgets\InputWidget
{
	private $defaultOptions = ['class' => 'form-control'];
    /** @var array */
    private $defaultClientOptions = [
        'height' => 300,
        'codemirror' => [
            'theme' => 'monokai'
        ],

       

    ];
    /** @var array */
    public $options = [];
    /** @var array */
    public $clientOptions = [];
    /** @var array */
    public $plugins = [];

    public $imgServer = 'http://uploads.im/api';

	public function init()
    {
    	//$this->defaultClientOptions['onImageUpload'] =  new JsExpression("function(files){summerNoteImgUpload(files[0])}");
        $this->options = array_merge($this->defaultOptions, $this->options);
        $this->clientOptions = array_merge($this->defaultClientOptions, $this->clientOptions);
        SummernoteAsset::register($this->getView());
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        
        echo $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);

        $this->clientOptions['onImageUpload'] =  new JsExpression("function(files){summerNoteImgUpload(files[0], '".$this->options['id']."','".$this->imgServer."')}");    
        $clientOptions = empty($this->clientOptions)
            ? null
            : Json::encode($this->clientOptions);
        $this->getView()->registerJs('jQuery( "#' . $this->options['id'] . '" ).summernote(' . $clientOptions . ');');
    	
    }
   
}
?>
