<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Poll */


$this->title = Yii::t('app', 'Create {modelClass} ', [
	'modelClass' => Yii::t('app/poll', 'Poll'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/poll', 'Poll'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if ($flash = Yii::$app->session->getFlash('error')) {
	echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}

?>
<div class="poll-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
		'answers' => $answers,
	]) ?>
	
</div>