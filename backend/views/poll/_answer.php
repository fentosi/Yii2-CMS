<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\Answer */

?>
<li class="list-group-item">
	<?= Html::activeHiddenInput($model, 'id', ['name' => 'Answer[id][]']) ?>
	<div class="row <?=($model->hasErrors() ? 'has-error' : '')?>">
		<div class="col-xs-1"></div>
		<div class="col-xs-9">
			<?= Html::activeTextInput($model, 'answer', ['class' => 'form-control', 'name' => 'Answer[answer][]']); ?>
			<?= Html::error($model, 'answer', ['class' => 'help-block']); ?>
		</div>
		<div class="col-xs-2"><span onclick="removeAnswer(this);" style="width: 100%;" class="btn btn-danger pull-right"><i class="glyphicon glyphicon-remove"></i></span></div>
	</div>
</li>
