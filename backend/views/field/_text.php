<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Field;

/* @var $this yii\web\View */
/* @var $model common\models\Field */

?>
<li class="list-group-item">
	<div class="row <?=($model->hasErrors() ? 'has-error' : '')?>">
		<div class="col-xs-2">
			<?= Html::activeHiddenInput($model, 'status', ['name' => 'Field['.$model->random_key.'][status]', 'class' => 'form-status']) ?>
			<span onclick="changeStatus(this)" class="btn btn-<?=($model->status ? 'primary' : 'default')?>" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Visible'); ?>"><i class="glyphicon glyphicon-eye-<?=($model->status ? 'open' : 'close')?>"></i></span>
			<span onclick="removeField(this);" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Delete'); ?>"><i class="glyphicon glyphicon-remove"></i></span>
		</div>
		<div class="col-xs-3">
			<?= Html::activeTextInput($model, 'name', ['class' => 'form-control', 'name' => 'Field['.$model->random_key.'][name]']); ?>
			<?= Html::error($model, 'name', ['class' => 'help-block']); ?>
		</div>
		<div class="col-xs-1 text-center">
			<?= Html::activeHiddenInput($model, 'type', ['name' => 'Field['.$model->random_key.'][type]']) ?>
			<?= Field::getFieldTypes()[$model->type] ?>
		</div>				
	</div>
</li>