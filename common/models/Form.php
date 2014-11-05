<?php

namespace common\models;

use Yii;
use yii\base\Model;

use yii\db\Expression;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "form".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property Fields[] $fields
 */
class Form extends \yii\db\ActiveRecord
{
	public $field_type;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'form';
	}
	
    /**
     * @inheritdoc
     */    
	public function behaviors() {
			
 		return [
	        	[
	            'class' => TimestampBehavior::className(),
	            'updatedAtAttribute' => 'updated_at',
	            'value' => new Expression('NOW()'),
				],
			];			

	}	
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'email'], 'required'],
			[['created_at', 'updated_at', 'deleted_at'], 'safe'],
			[['name', 'email'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app/form', 'ID'),
			'name' => Yii::t('app/form', 'Name'),
			'email' => Yii::t('app/form', 'E Mail'),
			'created_at' => Yii::t('app/form', 'Created At'),
			'updated_at' => Yii::t('app/form', 'Updated At'),
			'deleted_at' => Yii::t('app/form', 'Deleted At'),
		];
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->hasMany(Field::className(), ['form_id' => 'id'])
				->select('name, type, value, status')
        		->where('deleted_at IS NULL')
        		->orderBy('position');		
		;
	}
		
}
