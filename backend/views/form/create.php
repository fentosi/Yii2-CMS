<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Form */
/* @var $fields array common\models\Field */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('app/form', 'Form'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/form', 'Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'fields' => $fields,
    ]) ?>

</div>
