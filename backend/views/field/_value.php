<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $value the value of the field value */
/* @var $key the random_key of the field model */
/* @var $status the status of the field's value */

?>

<li>
	<div class="row">
		<div class="col-xs-9">
			<input type="text" value="<?= $value ?>" class="form-control input-sm" name="Value[<?=$key?>][value][]">
		</div>
		<div class="col-xs-3">
			<?= Html::hiddenInput("Value[{$key}][status][]", $status, ['class' => 'form-status']) ?>
			<span onclick="removeField(this);" class="btn btn-danger btn-sm pull-right" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Delete'); ?>"><i class="glyphicon glyphicon-remove"></i></span>
			<span onclick="changeStatus(this);" class="btn btn-<?=($status ? 'primary' : 'default')?> btn-sm pull-right mr6" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Visible'); ?>"><i class="glyphicon glyphicon-eye-<?=($status ? 'open' : 'close')?>"></i></span>			
		</div>
	</div>
</li>