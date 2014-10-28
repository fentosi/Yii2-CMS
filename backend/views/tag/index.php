<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/tag', 'Tags');
$this->params['breadcrumbs'][] = $this->title;

if ($flash = Yii::$app->session->getFlash('error')) {
    echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}


?>
<div class="tag-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => (Yii::t('app/tag', 'Tag')),
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'slug',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
