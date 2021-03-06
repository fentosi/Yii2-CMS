<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\models\Answer;

/* @var $this yii\web\View */
/* @var $model common\models\Poll */
/* @var $all count(common\models\Answer) */

$this->title = $model->question;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/poll', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="poll-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <table class="table table-striped table-bordered">
    	<tr>
    		<th class="col-xs-2"><?= Html::encode($model->getAttributeLabel('question'))?></th>
    		<td colspan="3"><?= Html::encode($model->question) ?></td>
    	</tr>
    	<tr>
    		<th rowspan="<?= count($answers)+1?>"><?= Yii::t('app/poll', 'Answers') ?></th>
    		<td colspan="3">&nbsp;</td>
    	</tr>
    	<?php
    		foreach ($answers as $answer) {
    			echo '
    	<tr>
    		<td>'.Html::encode($answer->answer).'</td>
    		<td class="col-xs-2 text-center">'.Html::encode($answer->votes_num).'</td>
    		<td class="col-xs-2 text-center">'.Html::encode(round($answer->votes_num/$votes_num_all*100)).'%</td>
    	</tr>    			
    			';
    		}
    	?>
    	<tr>
    		<th><?= $model->getAttributeLabel('status_on')?></th>
    		<td colspan="3"><?= (is_null($model->status_on) ? '<span class="not-set">' . Yii::t('yii', '(not set)') . '</span>' : $model->status_on) ?></td>
    	</tr>    	
    	<tr>
    		<th><?= $model->getAttributeLabel('status_off')?></th>
    		<td colspan="3"><?= (is_null($model->status_off) ? '<span class="not-set">' . Yii::t('yii', '(not set)') . '</span>' : $model->status_off) ?></td>
    	</tr>    	
    </table>

</div>
