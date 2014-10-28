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

	public static function addField() {
		$request = Yii::$app->request;
		
		$save = true;
		$error = [];
		$text = '';
		
		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
		}
		
		$model = new Field();
		
		$model->setScenario('add');
		
		if ($model->load($request->post())) {
			if ($model->validate()) {
						
				$text = Yii::$app->controller->renderPartial('//field/_'.$model->type, [
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
			'text' => $text,
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
