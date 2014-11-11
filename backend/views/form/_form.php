<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Field;

use backend\assets\FormAsset;
FormAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Form */
/* @var $form yii\widgets\ActiveForm */
/* @var $fields array common\models\Field */
?>

<div class="form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    
		<div class="field-form-field">
			<label class="control-label"><?= Yii::t('app/form', 'Add a field')?></label>
			<div class="row">
				<div class="col-xs-7 form-group">
					<input type="text" name="field_name" id="field-name" class="form-control" placeholder="<?= Yii::t('app/poll', 'Field name')?>">
					<div class="help-block"></div>
				</div>
				<div class="col-xs-4 form-group">
					<?= Html::dropDownList('field_type', '', Field::getFieldTypes(), [
						'prompt' => Yii::t('app/form', 'Field type'),
						'id' => 'field-type',
						'class' => 'form-control',
						]); ?>
						<div class="help-block"></div>
				</div>
				<div class="col-xs-1">
					<span class="btn btn-success pull-right" style="width: 100%;" onclick="addField(this);"><i class="glyphicon glyphicon-plus"></i></span>
				</div>
			</div>
			<div class="help-block"></div>
		</div>
		<div class="row">
			<div class="col-xs-2">&nbsp;</div>
			<div class="col-xs-3 text-center">
				<strong><?= Yii::t('app/poll', 'Field name')?></strong>
			</div>
			<div class="col-xs-1 text-center">
				<strong><?= Yii::t('app/poll', 'Field type')?></strong>
			</div>
			<div class="col-xs-6 text-center">
				<strong><?= Yii::t('app/poll', 'Field values')?></strong>
			</div>
		</div>


			<ul class="form-sortable list-group dd-list" id="table-form-fields">
			<?php
				foreach ($fields as $field) {
				
					if ($field->random_key == null) {
						$field->generateRandomKey();
					}
					
					echo $this->render('//field/_'.(in_array($field->type, ['select', 'radio', 'checkbox']) ? 'select' : $field->type), [
						'model' => $field,
					]);				
				}
			?>
			</ul>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
	$this->registerJs( "
	$('.form-sortable').sortable();
	$('.btn').tooltip();
	");
?>
