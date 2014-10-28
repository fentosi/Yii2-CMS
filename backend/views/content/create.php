<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Content */

$this->title = Yii::t('app', 'Create {modelClass}', [
	'modelClass' => Yii::t('app/content', 'Content'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/content', 'Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if ($flash = Yii::$app->session->getFlash('error')) {
    echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}

?>
<div class="content-create">

	<h1><?= Html::encode($this->title) ?></h1>
	
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>	

</div>
