<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\web\Controller;
use yii\base\View;

use kartik\widgets\DateTimePicker;
use kartik\widgets\TimePicker;

use backend\assets\PollAsset;
PollAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Poll */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="poll-form">

<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'question') ?>
		<div class="form-group field-poll-status_on">
			<div class="row">
				<div class="col-xs-6">
				<?= $form->field($model, 'status_on')->widget(DateTimePicker::classname(), [
				'options' => ['placeholder' => Yii::t('app', 'Pick a date')],
					'pluginOptions' => [
						'startDate' => date('Y-m-d'),
						'autoclose' => true,
						'todayHighlight' => true,
						'format' => 'yyyy-mm-dd hh:ii',						
					]
				]) ?>
				</div>							
				<div class="col-xs-6">
				<?= $form->field($model, 'status_off')->widget(DateTimePicker::classname(), [
				'options' => ['placeholder' => Yii::t('app', 'Pick a date')],
					'pluginOptions' => [
						'startDate' => date('Y-m-d'),
						'autoclose' => true,
						'todayHighlight' => true,
						'format' => 'yyyy-mm-dd hh:ii',
					]
				]) ?>	
				</div>											
			</div>
		</div>
		<div class="form-group field-poll-answers">
			<label class="control-label"><?=Yii::t('app/poll', 'Answers')?></label>
			<div class="row">
				<div class="col-xs-11">
					<input type="text" name="Poll[answers]" class="form-control" id="poll-answers" placeholder="<?=Yii::t('app/poll', 'Add an answer')?>">
				</div>
				<div class="col-xs-1">
					<span class="btn btn-success pull-right" style="width: 100%;" onclick="addAnswer(this);"><i class="glyphicon glyphicon-plus"></i></span>
				</div>
			</div>
			<div class="help-block"></div>
		</div>
		
		<ul class="poll-sortable list-group" id="table-poll-answers">

		<?php
		foreach ($answers as $ans) {			
			echo $this->render('/poll/_answer', [
				'model' => $ans,
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

$this->registerJs( 
	"
	$('.poll-sortable').sortable();
	
	$('.poll-sortable').sortable().bind('sortupdate', function() {
		renumberTable($('#table-poll-answers'));
	});	
	
	renumberTable($('#table-poll-answers'));
	"
);

?>

