<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Field;

/* @var $this yii\web\View */
/* @var $model common\models\Answer */

?>
<li class="list-group-item">
	<?= Html::activeHiddenInput($model, 'id', ['name' => 'Answer[id][]']) ?>
	<div class="row <?=($model->hasErrors() ? 'has-error' : '')?>">
		<div class="col-xs-2">
			<span onclick="" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Visible'); ?>"><i class="glyphicon glyphicon-eye-open"></i></span>
			<span onclick="removeField(this);" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Delete'); ?>"><i class="glyphicon glyphicon-remove"></i></span>
		</div>	
		<div class="col-xs-3">
			<?= Html::activeTextInput($model, 'name', ['class' => 'form-control', 'name' => 'field[name][]']); ?>
			<?= Html::error($model, 'name', ['class' => 'help-block']); ?>
		</div>
		<div class="col-xs-1 text-center">
			<?= Field::getFieldTypes()[$model->type] ?>
		</div>		
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="col-xs-10">
					<?= Html::textInput('name', null, [	'class' => 'form-control input-sm', 
														'placeholder' => Yii::t('app/form', 'Add a value to "{fieldName}" the field', ['fieldName' => $model->name]),
													]
										)
					?>
				</div>
				<div class="col-xs-2">
					<span class="btn btn-sm btn-success pull-right" style="width: 100%;" onclick="addFieldValue(this);"><i class="glyphicon glyphicon-plus"></i></span>
				</div>
			</div>
			<ul class="field-value-sortable list-unstyled" id="table-form-fields-values">
				
			</ul>
		</div>		
	</div>
</li>