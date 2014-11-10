<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
	 * Lists all User models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			   
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single User model.
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
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User(['scenario' => 'create']);
		
		if (Yii::$app->request->isPost) {
			
			//Generate Auth Key
			$model->generateAuthKey();
	
			if ($model->load(Yii::$app->request->post())) {
				
				//Generate password
				$model->setPassword($model->password_new);

				if ($model->save()) {
					return $this->redirect(['view', 'id' => $model->id]);
				} else {
					Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
				}
			} else {
				Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
			}		
		}
		
		return $this->render('create', [
			'model' => $model,
		]);
		
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		if (Yii::$app->request->isPost) {
		
			//if update, set the scenario for the validating rules
			$model->setScenario(($model !== null ? 'update' : 'create'));
		}

		if ($model->load(Yii::$app->request->post())) {
			
			//Change password, if needed
			if ($model->change_password) {
				$model->setPassword($model->password_new);
			}
			
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				 Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
			}
		}
		
		$model->type = array_search($model->type, User::getUserTypes());
		 
		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		
		if ($model->id == Yii::$app->user->id) {
			Yii::$app->getSession()->setFlash('error', Yii::t('app/user', "You can't delete yourself!"));
		} else {
			$model->deleted_at = date('Y-m-d H:i:s');
			
			if (!$model->save()) {
				Yii::$app->getSession()->setFlash('error', 'There is an error while deleting the data');
			} 		
		}
		
		
		return $this->redirect(['index']);
	}

	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$model = User::find()
			->where('deleted_at IS NULL AND id = '.$id)->one();
	
		if ($model !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
