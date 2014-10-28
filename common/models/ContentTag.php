<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "content_tag".
 *
 * @property integer $tag_id
 * @property integer $content_id
 * @property string $created_at
 *
 * @property Content $content
 * @property Tag $tag
 */
class ContentTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'content_id'], 'required'],
            [['tag_id', 'content_id'], 'integer'],
            [['created_at'], 'safe']
        ];
    }
    
    public static function deleteContentTag($id) {
    	return ContentTag::deleteAll('content_id = ' + $id);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => Yii::t('app', 'Tag ID'),
            'content_id' => Yii::t('app', 'Content ID'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(Content::className(), ['id' => 'content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }
}
