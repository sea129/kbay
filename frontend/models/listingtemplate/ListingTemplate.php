<?php

namespace frontend\models\listingtemplate;

use Yii;

/**
 * This is the model class for table "listing_template".
 *
 * @property integer $id
 * @property string $name
 *
 * @property EbayAccount[] $ebayAccounts
 */
class ListingTemplate extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'listing_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/lstingtemplate', 'ID'),
            'name' => Yii::t('app/lstingtemplate', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEbayAccounts()
    {
        return $this->hasMany(EbayAccount::className(), ['listing_template_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ListingTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ListingTemplateQuery(get_called_class());
    }
}
