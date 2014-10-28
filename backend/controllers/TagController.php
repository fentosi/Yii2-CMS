<?php

namespace backend\controllers;

use Yii;
use common\models\Tag;
use common\models\TagSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends Controller
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
	 * Lists all Tag models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TagSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Tag model.
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
	 * Creates a new Tag model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Tag();

		if (Yii::$app->request->isPost) {
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
			}
		}
		
		return $this->render('create', [
			'model' => $model,
		]);
		
	}

	/**
	 * Updates an existing Tag model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
			}
		}
		 
		return $this->render('update', [
			'model' => $model,
		]);

	}

	/**
	 * Deletes an existing Tag model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		
		if ($model) {
			$model->deleted_at = date('Y-m-d H:i:s');
			if (!$model->save()) {
				Yii::$app->getSession()->setFlash('error', 'There is an error while deleting the data');
			}
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Tag model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Tag the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$model = Tag::find()
			->where('deleted_at IS NULL AND id = '.$id)->one();

	
		if (($model !== null)) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
