<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "poll".
 *
 * @property integer $id
 * @property string $question
 * @property integer $status
 * @property string $status_on
 * @property string $status_on_time
 * @property string $status_off
 * @property string $status_off_time
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property Answer[] $answers
 */
class Poll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll';
    }
    
    /**
     * @inheritdoc
     */    
	public function behaviors() {
			
 		return [
	        	[
	            'class' => TimestampBehavior::className(),
	            'updatedAtAttribute' => 'updated_at',
	            'value' => new Expression('NOW()'),
				],
			];			

	}    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question'], 'required'],
            [['status'], 'integer'],
            [['status_on', 'status_on_time', 'status_off', 'status_off_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['question'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'question' => Yii::t('app/poll', 'Question'),
            'status' => Yii::t('app', 'Status'),
            'status_on' => Yii::t('app/poll', 'Status On'),
            'status_off' => Yii::t('app/poll', 'Status Off'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['poll_id' => 'id'])
        		->select('id, answer')
        		->where('deleted_at IS NULL')
        		->orderBy('position');
    }
    
    /**
     * @return array[]
     */    
	public function getAnswersIds($id)
    {
    	return Answer::find()
			->select(['id'])
			->where('deleted_at IS NULL AND poll_id = '.$id)
			->asArray()
			->all();
    }
}
