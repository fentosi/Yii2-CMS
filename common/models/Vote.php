<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vote".
 *
 * @property integer $id
 * @property integer $answer_id
 * @property integer $user_id
 * @property string $ip
 * @property string $created_at
 *
 * @property Answer $answer
 * @property User $user
 */
class Vote extends \yii\db\ActiveRecord
{

	public $votes_num;
	public $answer;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer_id', 'ip'], 'required'],
            [['answer_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['ip'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'answer_id' => Yii::t('app', 'Answer ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'ip' => Yii::t('app', 'Ip'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answer::className(), ['id' => 'answer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
