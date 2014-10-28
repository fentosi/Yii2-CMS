<?php

namespace backend\controllers;

use Yii;
use common\models\Poll;
use common\models\Answer;
use common\models\Vote;
use common\models\PollSearch;

use yii\base\DynamicModel;
use yii\base\Model;

use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Html;



/**
 * PollController implements the CRUD actions for Poll model.
 */
class PollController extends Controller
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
	 * Lists all Poll models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PollSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Poll model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{	
	
		return $this->render('view', [
			'model' => $this->findModel($id),
			'answers' => Answer::getVotesNum($id),
			'all' =>  Answer::getVotesAllNum($id),
		]);
	}

	/**
	 * Creates a new Poll model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Poll();
		
		$save = true;
		$answers = [];
		
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		
		try {
		
			$save = $this->createUpdateModel($model, $answers);
				
			if ($save) {
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
			'answers' => $answers,
		]);
		
	}

	/**
	 * Updates an existing Poll model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
	
		$answers = [];   
	
		$model = $this->findModel($id);
		
		if ($model !== null) {
						
			$connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();			
			
			try {
			
				//Answer::updateAll(['deleted_at' => date('Y-m-d H:i:s')],'poll_id = '.$id);		   
				$save = $this->createUpdateModel($model, $answers);
				
			   	if ($save) {
					$transaction->commit();
					return $this->redirect(['view', 'id' => $model->id]);				
				} else {
					$transaction->rollBack();	
				}
			} catch(Exception $e) {
		   		$transaction->rollBack();
		   		throw $e;
			}
		}
		
		if (count($answers) == 0) {
			$answers = $model->answers;
		}
	
		return $this->render('update', [
				'model' => $model,
				'answers' => $answers,
			]);	 
		
	}
	
	private function createUpdateModel(&$model, &$answers) {
	
		$save = true;
	
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
				
			$pos = 1;
			$ans_ids = $del_ids = [];
			
			foreach (Yii::$app->request->post("Answer")['answer'] as $key => $ans) {
				
				if (!empty(Yii::$app->request->post("Answer")['id'][$key])) {
					$answer = Answer::findOne(Yii::$app->request->post("Answer")['id'][$key]);
					$ans_ids[] = Yii::$app->request->post("Answer")['id'][$key];
				} else {
					$answer = new Answer();
					$answer->poll_id = $model->id;
				}
				
				$answer->answer = strip_tags($ans);
				$answer->position = $pos++;
				
				$caller = next(debug_backtrace())['function'];
				
				if (!empty($answer->id)) {
					//Update						
					if ($answer->update() !== false) {
						if ($model->update() === false) {
							$save = false;
						} 
					} else {
						$save = false;
					}
				} elseif ($answer->validate()) {
					
					//Create
					$model->link('answers', $answer);
					$ans_ids[] = $answer->id;
				} else {
					$save = false;
				}
				
				$answers[] = $answer;
			}
			
			if ($save) {
				//Delete the deleted answers
				foreach (Poll::getAnswersIds($model->id) as $ids) {
					if (!in_array($ids['id'], $ans_ids)) {
						$del_ids[] = $ids['id'];
					}
				}
				
				if (count($del_ids) > 0) {
					Answer::updateAll(['deleted_at' => date('Y-m-d H:i:s')],['id' => $del_ids]);
					Vote::deleteAll(['answer_id' => $del_ids]);
				}
			}
		} else {
			$save = false;
		}
		
		return $save;	
	}
	

	/**
	 * Deletes an existing Poll model.
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
				$model->save();
				Answer::updateAll(['deleted_at' => date('Y-m-d H:i:s')],'poll_id = '.$id);
				
				$connection ->createCommand('DELETE vote FROM vote, answer, poll WHERE answer_id = answer.id AND poll_id = poll.id AND poll_id = '.$id)->execute();
				
				$transaction->commit();
				
			} catch(Exception $e) {
		   		$transaction->rollBack();
		   		throw $e;
			}
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Poll model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Poll the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$model = Poll::find()
			->select(['id','question', 'status', 'status_on','status_on_time','status_off','status_off_time', 'created_at','updated_at'])
			->where('deleted_at IS NULL AND id = '.$id)->one();
		
		if ($model !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	/**
	 *
	 */
	
	public function actionAddAnswer()
	{
		$save = true;
		$text = [];
	
		$request = Yii::$app->request;
		
		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
		}
		
		$post = $request->post();
		
		if (isset($post['answer'])) {
			$answer = new Answer();
			$answer->answer = strip_tags($post['answer']);
			$answer->setScenario('add');
			if ($answer->validate()) {
				$text[] = $this->renderPartial('_answer', [
					'model' => $answer,
				]);			
			} else {
				$save = false;
				foreach ($answer->errors as $error) {
					foreach ($error as $e) {
						$text[] = $e;
					}
				}
			}
		} else {
			$save = false;
			$text[] = Yii::t('app/poll', 'No answer');
		}
		
		return array(
			'ok' => $save,
			'text' => implode('<br>', $text),
		);
			
	}	
	
}
