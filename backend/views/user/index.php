<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
//use yii\grid\GridView;
use kartik\grid\GridView;
use common\models\User;


/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/user', 'Users');
$this->params['breadcrumbs'][] = $this->title;

if ($flash = Yii::$app->session->getFlash('error')) {
    echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('app/user', 'User'),
	]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'name',
            'email:email',
			[
				'attribute' => 'type', 
				'vAlign' => 'middle',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => User::getUserTypes(), 
				'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear'=>true],
				],
				'filterInputOptions' => ['placeholder' => Yii::t('app/user', 'Any user type') ],
				'format' => 'raw'
			],
			['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
