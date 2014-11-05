<?php

namespace common\models;

use Yii;
use yii\web\Response;
use yii\web\View;
use yii\base\Controller;

/**
 * This is the model class for table "field".
 *
 * @property integer $id
 * @property integer $form_id
 * @property string $type
 * @property string $value
 * @property integer $position
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property Form $form
 */
class Field extends \yii\db\ActiveRecord
{
	public $random_key;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'field';
	}
	
	/**
	 * @inheritdoc
	 */
	public static function getFieldTypes()
	{
		return [
			'input' => Yii::t('app/form', 'Input'),
			'select' => Yii::t('app/form', 'Select'),
			'checkbox' => Yii::t('app/form', 'Checkbox'),
			'radio' => Yii::t('app/form', 'Radio'),
			'text' => Yii::t('app/form', 'Text'),
			'space' => Yii::t('app/form', 'Space'),
		];
	}		

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['form_id'], 'required', 'except' => 'add'],
			[['name'], 'required', 'when' =>  function($model) {
												return $model->type != 'space';
											}],
			[['type'], 'required'],
			[['type'], 'in', 'range' => array_keys(self::getFieldTypes())],
			[['form_id', 'position', 'status'], 'integer'],
			[['type', 'value', 'name'], 'string'],
			[['created_at', 'updated_at', 'deleted_at'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app/form', 'ID'),
			'form_id' => Yii::t('app/form', 'Form ID'),
			'type' => Yii::t('app/form', 'Type'),
			'value' => Yii::t('app/form', 'Value'),
			'position' => Yii::t('app/form', 'Position'),
			'status' => Yii::t('app/form', 'Status'),
			'created_at' => Yii::t('app/form', 'Created At'),
			'updated_at' => Yii::t('app/form', 'Updated At'),
			'deleted_at' => Yii::t('app/form', 'Deleted At'),
		];
	}
	
	/**
     * Generates random key for the field
     */
    public function generateRandomKey()
    {
		$this->random_key = Yii::$app->security->generateRandomString(6);
    }	
	
	/**
	 * Add a value input to the field
	 * @return array
	 */	

	public static function addFieldValue() {
		$request = Yii::$app->request;
		
		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
		}
		
		//Load the _value view
		$html = Yii::$app->controller->renderPartial('//field/_value', [
			'value' => $request->post('value'),
			'key' => $request->post('key'),
			'status' => 1,
		]);
				
		return [
			'ok' => true,
			'text' => $html,
		];
	}
	
	
	/**
	 * Add a field to the form	
	 * @return array
	 */	

	public static function addField() {
		$request = Yii::$app->request;
		
		$save = true;
		$error = [];
		$html = '';
		
		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
		}
		
		$model = new Field();
		
		//set scenario for the rule
		$model->setScenario('add');
		
		//generate a random key, which connects the data together in the form
		$model->generateRandomKey();
		
		if ($model->load($request->post())) {
			
			//Set default status to visible
			$model->status = 1;
		
			if ($model->validate()) {
						
				//load the view, depends on the type
				$html = Yii::$app->controller->renderPartial('//field/_'.(in_array($model->type, ['select', 'radio', 'checkbox']) ? 'select' : $model->type), [
					'model' => $model,
				]);
				
			} else {
				$save = false;
				$error = $model->errors;
			}
		} else {
			$save = false;
			$error[] = 'ERROR';
		}
		
		return [
			'ok' => $save,
			'error' => $error,
			'text' => $html,
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getForm()
	{
		return $this->hasOne(Form::className(), ['id' => 'form_id']);
	}
}
