<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Poll */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
	'modelClass' => Yii::t('app', 'Poll'),
]) . ' ' . $model->question;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->question, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

if ($flash = Yii::$app->session->getFlash('error')) {
	echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}
?>
<div class="poll-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
		'answers' => $answers,
	]) ?>

</div>
