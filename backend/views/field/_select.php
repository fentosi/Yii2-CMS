<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Field;

/* @var $this yii\web\View */
/* @var $model common\models\Field */

$values = json_decode($model->value);

?>
<li class="list-group-item">
	<div class="row">
		<div class="col-xs-2">
			<?= Html::activeHiddenInput($model, 'status', ['name' => 'Field['.$model->random_key.'][status]', 'class' => 'form-status']) ?>
			<span onclick="changeStatus(this)" class="btn btn-<?=($model->status ? 'primary' : 'default')?>" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Visible'); ?>"><i class="glyphicon glyphicon-eye-<?=($model->status ? 'open' : 'close')?>"></i></span>
			<span onclick="removeField(this);" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Delete'); ?>"><i class="glyphicon glyphicon-remove"></i></span>
		</div>
		<div class="col-xs-3 <?=($model->hasErrors() ? 'has-error' : '')?>">
			<?= Html::activeTextInput($model, 'name', ['class' => 'form-control', 'name' => 'Field['.$model->random_key.'][name]']); ?>
			<?= Html::error($model, 'name', ['class' => 'help-block']); ?>
		</div>
		<div class="col-xs-1 text-center">
			<?= Html::activeHiddenInput($model, 'type', ['name' => 'Field['.$model->random_key.'][type]']) ?>
			<?= Field::getFieldTypes()[$model->type] ?>
		</div>		
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="col-xs-10">
					<?= Html::textInput('value', null, [
									'class' => 'form-control input-sm add-field-value', 
									'placeholder' => Yii::t('app/form', 'Add a value to "{fieldName}" the field', ['fieldName' => $model->name]),
								]
							)
					?>
				</div>
				<div class="col-xs-2">
					<span class="btn btn-sm btn-success pull-right" style="width: 100%;" onclick="addFieldValue(this, '<?= $model->random_key ?>');"><i class="glyphicon glyphicon-plus"></i></span>
				</div>
			</div>
			<ul class="field-value-sortable list-unstyled">
			<?
				if (isset($values->value)) {
					foreach ($values->value as $key => $v) {
						echo $this->render('//field/_value', [
							'value' => $values->value[$key],
							'key' => $model->random_key,
							'status' => $values->status[$key],
						]);
					}				
				}
			?>
			</ul>
		</div>		
	</div>
</li>