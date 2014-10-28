<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\web\Controller;
use yii\base\View;

use kartik\widgets\DatePicker;
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
		<?='
		
		<div class="form-group field-poll-status_on">
			<label class="control-label">'.Yii::t('app/poll', 'Status On').'</label>
			<div class="row">
				<div class="col-xs-6">
			'.DatePicker::widget([
				'model' => $model,
				'attribute' => 'status_on',
				'options' => [
					'placeholder' => Yii::t('app', 'Pick a date'),
				],
				'pluginOptions' => [
					'startDate' => date('Y-m-d'),
					'autoclose' => true,
					'todayHighlight' => true,
				],
				'pluginEvents' => [
					'changeDate' => "function (e) {
						var timepicker = $(this).parent().parent().find('input.form-control[data-plugin-name=\"timepicker\"]');
						if (timepicker && timepicker.val().trim() == '') {
							timepicker.val('00:00');
						}
					}",
					'clearDate' => "function (e) {
						var timepicker = $(this).parent().parent().find('input.form-control[data-plugin-name=\"timepicker\"]');
						if (timepicker) {
							timepicker.val('');
						}
					}",					
				]
			]).'				
				</div>			
				<div class="col-xs-6">
				'.TimePicker::widget([
				'model' => $model,
				'attribute' => 'status_on_time',
				'pluginOptions' => [
					'showMeridian' => false,
					'defaultTime' => false,
				]
			]).
			'	
				</div>							
			</div>
			<div class="help-block"></div>
		</div>';?>
		
		<?='
		<div class="form-group field-poll-status_off">
			<label class="control-label">'.Yii::t('app/poll', 'Status Off').'</label>
			<div class="row">
				<div class="col-xs-6">
			'.DatePicker::widget([
				'model' => $model,
				'attribute' => 'status_off',
				'options' => [
					'placeholder' => Yii::t('app', 'Pick a date'),
				],
				'pluginOptions' => [
					'startDate' => date('Y-m-d'),
					'autoclose' => true,
					'todayHighlight' => true,
				],
				'pluginEvents' => [
					'changeDate' => "function (e) {
						var timepicker = $(this).parent().parent().find('input.form-control[data-plugin-name=\"timepicker\"]');
						if (timepicker && timepicker.val().trim() == '') {
							timepicker.val('23:59');
						}
					}",
					'clearDate' => "function (e) {
						var timepicker = $(this).parent().parent().find('input.form-control[data-plugin-name=\"timepicker\"]');
						if (timepicker) {
							timepicker.val('');
						}
					}",
				]
			]).'				
				</div>			
				<div class="col-xs-6">
				'.TimePicker::widget([
				'model' => $model,
				'attribute' => 'status_off_time',
				'pluginOptions' => [
					'showMeridian' => false,
					'defaultTime' => false,
					]
				]).
			'	
				</div>							
			</div>
		</div>
		
		<div class="form-group field-poll-answers">
			<label class="control-label">'.Yii::t('app/poll', 'Answers').'</label>
			<div class="row">
				<div class="col-xs-11">
					<input type="text" name="Poll[answers]" class="form-control" id="poll-answers" placeholder="'.Yii::t('app/poll', 'Add an answer').'">
				</div>
				<div class="col-xs-1">
					<span class="btn btn-success pull-right" style="width: 100%;" onclick="addAnswer(this);"><i class="glyphicon glyphicon-plus"></i></span>
				</div>
			</div>
			<div class="help-block"></div>
		</div>
		
		<ul class="poll-sortable list-group" id="table-poll-answers">
		';

		foreach ($answers as $ans) {			
			echo $this->render('/poll/_answer', [
				'model' => $ans,
			]);
		}
		
		echo '
		</ul>
		';
		?>
		
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

