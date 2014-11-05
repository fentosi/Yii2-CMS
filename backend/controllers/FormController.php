<?php

namespace backend\controllers;

use Yii;
use common\models\Form;
use common\models\Field;
use common\models\FormSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Html;


/**
 * FormController implements the CRUD actions for Form model.
 */
class FormController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],		
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all Form models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new FormSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Form model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Form model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Form();
		
		$fields = [];
		$save = true;			

		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		
		try {
			if ($this->createUpdateModel($model, $fields)) {
				$transaction->commit();
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				$transaction->rollBack();
			}		
		
		} catch(Exception $e) {
   			$transaction->rollBack();
	   		throw $e;
		} 

		
		return $this->render('create', [
			'model' => $model,
			'fields' => $fields,
		]);
		
	}

	/**
	 * Updates an existing Form model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		$fields = [];
		
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
				
		try {
			//Delete all the fields
			Field::updateAll(['deleted_at' => date('Y-m-d H:i:s')],'form_id = '.$id);		
		
			if ($this->createUpdateModel($model, $fields)) {
				$transaction->commit();
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				$transaction->rollBack();
			}		
		
		} catch(Exception $e) {
   			$transaction->rollBack();
	   		throw $e;
		} 					
		
		
		//If no fields, load it from the Form model
		if (count($fields) == 0) {
			$fields = $model->fields;
		}		
		
		return $this->render('update', [
			'model' => $model,
			'fields' => $fields,
		]);
		
	}
	
	/**
	 * Create or update the Form model, save the Fields model which belongs to the Form
	 * 
	 * @param model fields by reference
	 * @return boolean
	 */	
	
	private function createUpdateModel(&$model, &$fields) {
	
		$save = true;
		
		$position = 0;

		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->save()) {
				
				$postFields = Yii::$app->request->post('Field');
				$postValues = Yii::$app->request->post('Value');
				
				foreach($postFields as $key => $formField) {
				
					$field = new Field();
					
					$field->name = Html::encode(strip_tags($postFields[$key]['name']));
					$field->type = $postFields[$key]['type'];
					$field->position = ++$position;
					$field->form_id = $model->id;
					$field->status = $postFields[$key]['status'];
					
					$field->value = (isset($postValues[$key]) ? json_encode($postValues[$key]) : null);
					
					if ($field->validate()) {
						$model->link('fields', $field);
					} else {
						$save = false;
					}
					
					$fields[] = $field;
				}
				unset($formField, $key);		
			}		
		} else {
			$save = false;
		}
					
		return $save;	
	}	

	/**
	 * Deletes an existing Form model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		
		if ($model) {
		
			$connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();		
		
			$model->deleted_at = date('Y-m-d H:i:s');
			try {
				if ($model->save()) {
					//Delete all the fields
					Field::updateAll(['deleted_at' => date('Y-m-d H:i:s')],'form_id = '.$id);		
					$transaction->commit();				
				} else {
					Yii::$app->getSession()->setFlash('error', 'There is an error while deleting the data');
				}
				
				
			} catch(Exception $e) {
		   		$transaction->rollBack();
		   		throw $e;
			}
		}		

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Form model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Form the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Form::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	* Create a field for the form
	*/
	public function actionAddField() {
		return Field::addField();
	}

	/**
	* Create value for the field
	*/	
	public function actionAddFieldValue() {
		return Field::addFieldValue();
	}
	
}
