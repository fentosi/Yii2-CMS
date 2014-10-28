<?php

namespace backend\controllers;

use Yii;
use common\models\Content;
use common\models\ContentTag;
use common\models\ContentSearch;
use common\models\Tag;

use yii\helpers\ArrayHelper;

use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends Controller
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all Content models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ContentSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Content model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$model->user;
		$model->tags = implode(', ', ArrayHelper::map($model->getTags(), 'id', 'name'));
	
		return $this->render('view', [
			'model' => $model,
		]);
	}

	/**
	 * Creates a new Content model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Content();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Content model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		
		$model = $this->findModel($id);
		
		$model->tags = implode(', ', ArrayHelper::map($model->getTags(), 'id', 'name'));

		if ($model->load(Yii::$app->request->post())) {
			

			$connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();			

			try {
			
				$save = true;
						
				if ($model->save()) {
				
					$tags = explode(',', $model->tags);
					
					$exists_tags = array_flip(ArrayHelper::map(Tag::getTags($tags), 'id', 'name'));					
					
					//delete all the tags related to the content from content_tag table
					if (contentTag::deleteContentTag($model->id)) {
					
						foreach($tags as $t) {
							$tag_id = 0;
							$t = trim($t);
							if (mb_strlen($t) > 0) {
								if (isset($exists_tags[$t]) && is_numeric($exists_tags[$t])) {
									$tag_id = $exists_tags[$t];
								} else {
									$tag = new Tag();
									$tag->name = $t;
									$tag->slug = $t;
									if ($tag->save()) {
										$tag_id = $tag->id;
									} else {
										$save = false;
										Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
										break;
									}
								}
								
								if ($tag_id > 0) {
									$contentTag = new ContentTag();
									$contentTag->content_id = $model->id;
									$contentTag->tag_id = $tag_id;
									if (!$contentTag->save()) {
										$save = false;
										Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
										break;
									}
								}
							}	
						}					
					} else {
						Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
					}
					
				}
				
				if ($save) {
					$transaction->commit();
					return $this->redirect(['view', 'id' => $model->id]);
				} 
				
			} catch(Exception $e) {
		   		$transaction->rollBack();
		   		throw $e;
			}
		
		}		
		
		return $this->render('update', [
			'model' => $model,
		]);
		
	}

	/**
	 * Deletes an existing Content model.
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
	
	/*
	public function actionTagList($search = null, $id = null) {
	
		$return = ['more' => false];
	
		if (Yii::$app->request->isAjax) {
		
			Yii::$app->response->format = Response::FORMAT_JSON;
			
			if (!is_null($search) && mb_strlen($search) >= 3) {
				
				$tags = Tag::getTags($search, 'text');
				
				$return['results'] = $tags;
			
			}
		}
		
		return $return;
	}
	*/

	/**
	 * Finds the Content model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Content the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Content::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
}
