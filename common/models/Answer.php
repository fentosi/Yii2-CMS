<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property integer $id
 * @property integer $poll_id
 * @property string $answer
 * @property integer $position
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property Poll $poll
 * @property Vote[] $votes
 */
class Answer extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['poll_id'], 'required', 'except' => 'add'],
            [['poll_id', 'position', 'status'], 'integer', 'except' => 'add'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['answer'], 'string', 'max' => 255],
            ['answer','trim'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/poll', 'ID'),
            'poll_id' => Yii::t('app/poll', 'Poll ID'),
            'answer' => Yii::t('app/poll', 'Answer'),
            'position' => Yii::t('app/poll', 'Position'),
            'status' => Yii::t('app/poll', 'Status'),
            'created_at' => Yii::t('app/poll', 'Created At'),
            'updated_at' => Yii::t('app/poll', 'Updated At'),
            'deleted_at' => Yii::t('app/poll', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::className(), ['answer_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotesNum($id)
    {
    	return Vote::find()
			->select(['answer','answer.id','COUNT(vote.id) AS cnt'])
			->joinWith('answer', true, 'RIGHT JOIN')
			->where('poll_id = '.$id.' AND deleted_at IS NULL ')
			->groupBy(['answer.id'])
			->orderBy('position')
			->all();
    }  
    

    /**
     * @return integer
     */
    public function getVotesAllNum($id)
    {
    	return Vote::find()
    		->joinWith('answer')
			->where('poll_id = '.$id)
			->count('1');
    }  

      
}
