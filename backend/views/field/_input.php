<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

use common\models\Field;

/* @var $this yii\web\View */
/* @var $model common\models\Answer */

?>
<li class="list-group-item">
	<?= Html::activeHiddenInput($model, 'id', ['name' => 'field[id][]']) ?>
	<div class="row <?=($model->hasErrors() ? 'has-error' : '')?>">
		<div class="col-xs-3">
			<?= Html::activeTextInput($model, 'name', ['class' => 'form-control', 'name' => 'field[name][]']); ?>
			<?= Html::error($model, 'name', ['class' => 'help-block']); ?>
		</div>
		<div class="col-xs-2 text-center">
			<?= Field::getFieldTypes()[$model->type] ?>
		</div>		
		<div class="col-xs-2"><span onclick="removeField(this);" class="btn btn-danger pull-right"><i class="glyphicon glyphicon-remove"></i></span></div>
	</div>
</li>
