<?php

use yii\helpers\Html;
use yii\base\View;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use common\models\User;

use backend\assets\UserAsset;
UserAsset::register($this);


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Create {modelClass}', [
	'modelClass' => Yii::t('app/user', 'User'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if ($flash = Yii::$app->session->getFlash('error')) {
    echo Alert::widget(['options' => ['class' => 'alert alert-danger'], 'body' => $flash]);
}

?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin([]); ?>
	
			<?= $form->field($model, 'name') ?>
			
			<?= $form->field($model, 'email') ?>
			
			<?= $form->field($model, 'type')->dropDownList(User::getUserTypes())  ?>
			
			<?= $form->field($model, 'password_new', ['enableClientValidation' => false])->passwordInput() ?>
				
			<?= $form->field($model, 'password_confirm', ['enableClientValidation' => false])->passwordInput() ?>
				
			<div class="form-group">
		        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-success']) ?>
		</div>
	<?php ActiveForm::end(); ?>

</div>


