<?php
  namespace frontend\components;

  use Yii;
  class EparcelHelper extends \yii\base\Object
  {
    //const TEMPLATE_FILE = '';

    private $_objPHPExcel;

    private $_excelRow;

    public function getObjPHPExcel()
    {
      return $this->_objPHPExcel;
    }

    public function __construct($config=[]){
      $this->_objPHPExcel = \PHPExcel_IOFactory::load('./labels/'.'eparcel_template20151023.xlsx');
      $this->_excelRow = 2;
      parent::__construct($config);
    }

    public function init(){
      parent::init();
    }
    public function addExcelRow($order, $weight){

      $this->_objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A'.$this->_excelRow, $weight)
             ->setCellValue('B'.$this->_excelRow, $order['recipient_name'])
             ->setCellValue('D'.$this->_excelRow, $order['recipient_phone'])
             ->setCellValue('F'.$this->_excelRow, $order['recipient_address1'])
             ->setCellValue('G'.$this->_excelRow, $order['recipient_address2'])
             ->setCellValue('I'.$this->_excelRow, $order['recipient_city'])
             ->setCellValue('J'.$this->_excelRow, $order['recipient_state'])
             ->setCellValue('K'.$this->_excelRow, $order['recipient_postcode'])
             ->setCellValue('L'.$this->_excelRow, $order['ebay_order_id'])
             ->setCellValue('N'.$this->_excelRow, $order['buyer_id'])
             ;
      $this->_excelRow++;
    }
  }
 ?>
