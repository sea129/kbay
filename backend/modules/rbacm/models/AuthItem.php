<?php

namespace app\modules\rbacm\models;

use Yii;
use yii\rbac\Item;
use yii\helpers\Json;

class AuthItem extends \yii\base\Model
{
    public $name;
    public $type;
    public $description;
    public $rule_name;
    public $data;

    private $_item;

    public function __construct($item, $config = [])
    {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->rule_name = $item->ruleName;
            $this->data = $item->data === null ? null : Json::encode($item->data);
        }
        parent::__construct($config);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['search'] = ['name', 'description','rule_name'];
        $scenarios['create'] = ['name', 'description','rule_name','data'];
        $scenarios['update'] = ['name', 'description','rule_name','data'];
        return $scenarios;
    }
    public function rules()
    {
        return [
            [['rule_name'], 'in',
                'range' => array_keys(Yii::$app->authManager->getRules()),
                'message' => 'Rule not exists'],
            [['name', 'type'], 'required'],
            [['name'], 'unique', 'when' => function() {
                return $this->isNewRecord || ($this->_item->name != $this->name);
            }],
            [['type'], 'integer'],
            [['description', 'data', 'rule_name'], 'default'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    public function unique()
    {
        $authManager = Yii::$app->authManager;
        $value = $this->name;
        if ($authManager->getRole($value) !== null || $authManager->getPermission($value) !== null) {
            $message = Yii::t('yii', '{attribute} "{value}" has already been taken.');
            $params = [
                'attribute' => $this->getAttributeLabel('name'),
                'value' => $value,
            ];
            $this->addError('name', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
        }
    }
    public function save()
    {
        if($this->validate()){
            $manager = Yii::$app->authManager;
            if ($this->_item === null) {
                if ($this->type == Item::TYPE_ROLE) {
                    $this->_item = $manager->createRole($this->name);
                } else {
                    $this->_item = $manager->createPermission($this->name);
                }
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName = $this->rule_name;
            $this->_item->data = $this->data === null || $this->data === '' ? null : Json::decode($this->data);
            if ($isNew) {
                $manager->add($this->_item);
            } else {
                $manager->update($oldName, $this->_item);
            }
            return true;
        }else{
            return false;
        }
    }


    public function getIsNewRecord()
    {
        return $this->_item === null;
    }


}
	

   


?>