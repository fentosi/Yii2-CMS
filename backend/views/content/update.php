<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;	

/* @var $this yii\web\View */
/* @var $model common\models\Content */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app/content', 'Content'),
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/content', 'Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

if ($flash = Yii::$app->session->getFlash('error')) {
    echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}

?>
<div class="content-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
