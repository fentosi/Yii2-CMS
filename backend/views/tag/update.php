<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
	'modelClass' => Yii::t('app/tag', 'Tag'),
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/tag', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

if ($flash = Yii::$app->session->getFlash('error')) {
    echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}


?>
<div class="tag-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>
		
		<?= $form->field($model, 'name', ['inputOptions' => [
						'onblur' => 'copyData(this, \'tag-slug\')',
						'class' => 'form-control',
					],	
				]
			) 
		?>
		
		<?= $form->field($model, 'slug') ?>
		
		<div class="form-group">
				<?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
		</div>
	
	<?php ActiveForm::end(); ?>
</div>
