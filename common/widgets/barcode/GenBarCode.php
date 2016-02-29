<?php

namespace common\widgets\barcode;

use Yii;
use yii\helpers\Html;
use BCGColor;
use BCGDrawing;
use BCGcode128;
use BCGFontFile;
//use frontend\widgets\barcode\BCGColor;

//require_once('class'. DIRECTORY_SEPARATOR .'BCGFontFile.php');
//require_once('class'. DIRECTORY_SEPARATOR .'BCGColor.php');
//require_once('class'. DIRECTORY_SEPARATOR .'BCGDrawing.php');
//include_once('class' . DIRECTORY_SEPARATOR . 'BCGcode128.barcode.php');

class GenBarCode extends \yii\base\Widget
{
	public $options = array();
	public $message;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$color_black = new BCGColor(0, 0, 0);
        $color_white = new BCGColor(255, 255, 255);

		$filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);
        $code_generated = new BCGcode128();
        $this->baseCustomSetup($code_generated);
        $code_generated->setScale(2);
        $code_generated->setBackgroundColor($color_white);
        $code_generated->setForegroundColor($color_black);
        $code_generated->parse($this->message);
        $drawing = new BCGDrawing('', $color_white);
        $drawing->setBarcode($code_generated);
        $drawing->setRotationAngle(0);
        $drawing->setDPI(72);
        $drawing->draw();
        $drawing->finish($filetypes['PNG']);
	}

	public function baseCustomSetup($barcode)
    {
        $font_dir =  'font';
        $barcode->setThickness(max(9, min(90, 25)));
        $font = new BCGFontFile(__DIR__.'/font/Arial.ttf', 15);
        $barcode->setFont($font);
    }


}
 ?>
