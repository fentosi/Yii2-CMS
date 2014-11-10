<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use dosamigos\tinymce\TinyMce;

use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;

use yii\web\JsExpression;

use common\models\Content;
use common\models\Tag;


/* @var $this yii\web\View */
/* @var $model common\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<?php $form = ActiveForm::begin([]); ?>
		<div class="col-xs-9">

			<?= $form->field($model, 'title', ['inputOptions' => [
							'onblur' => 'copyData(this, \'content-slug\')',
							'class' => 'form-control',
						],	
					]
				)->textInput(['maxlength' => 255]) 
			?>
			
			<?= $form->field($model, 'lead')->textarea(['rows' => 6])->widget(TinyMce::className(), [
					'options' => ['rows' => 6],
					'language' => Yii::$app->language,
					'clientOptions' => [
						'plugins' => [
							"advlist autolink lists link image charmap print preview hr anchor pagebreak",
							"searchreplace wordcount visualblocks visualchars code fullscreen",
							"insertdatetime media nonbreaking save table contextmenu directionality",
							"paste textcolor"						],
						'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview"
					]
				]) ?>
			
			<?= $form->field($model, 'content')->textarea(['rows' => 6])->widget(TinyMce::className(), [
					'options' => ['rows' => 10],
					'language' => Yii::$app->language,
					'clientOptions' => [
						'plugins' => [
							"advlist autolink lists link image charmap print preview hr anchor pagebreak",
							"searchreplace wordcount visualblocks visualchars code fullscreen",
							"insertdatetime media nonbreaking save table contextmenu directionality",
							"paste textcolor"
						],
						'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview"
					]
				]) ?>
			<div class="form-group field-content-tag">
							
				<?= $form->field($model, 'tags')->widget(Select2::classname(), [
						'language' => Yii::$app->language,
						'options' => ['placeholder' => Yii::t('app/tag', 'Select a tag')],
						'pluginOptions' => [
							'allowClear' => true,
							'tags' => array_map(function($x){ return $x['name']; }, Tag::getTagsByName()),
						],
					]);
				?>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?=Yii::t('app/content', 'SEO')?></h3>
				</div>
				<div class="panel-body">
					<?= $form->field($model, 'slug')->textInput(['maxlength' => 255]) ?>		
					
					<?= $form->field($model, 'seo_title')->textInput(['maxlength' => 255]) ?>
					
					<?= $form->field($model, 'seo_meta_desc')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>		

		    <div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/content', 'Create') : Yii::t('app/content', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>

		</div>
		
		<div class="col-xs-3">
			
			<?= $form->field($model, 'status')->dropDownList(Content::getContentStatuses()) ?>
			
			<?= $form->field($model, 'published_at')->widget(DateTimePicker::classname(), [
					'options' => ['placeholder' => Yii::t('app', 'Now')],
					'pluginOptions' => [
						'autoclose' => true
					]
				]);	
			?>
			
			<?= $form->field($model, 'comment')->dropDownList(Content::getCommentStatuses()) ?>
			
		</div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
