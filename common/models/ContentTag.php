<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

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
    
	/**
	 * Delete All tags from a Content
	 * @param content_id
	 * @return boolean
	 */    
    public static function deleteContentTag($content_id) {
    	return ContentTag::deleteAll('content_id = ' + $content_id);
    }
    
	/**
	 * Save a Tag to the Content
	 * @param model_id	 
	 * @param tag_id
	 * @return boolean
	 */
	public static function saveContentTag($model_id, $tag_id)
	{
		$contentTag = new ContentTag();
		$contentTag->content_id = $model_id;
		$contentTag->tag_id = $tag_id;
		return $contentTag->save();
	}   
	
	/**
	 * Save a all Tag to the Content
 	 * @param int model_id
	 * @param strin tags seperated by comma
	 * @return boolean
	 */
	public static function saveAllContentTag($model_id, $tags)
	{
		$return = true;
		
		$tags = explode(',', $tags);					
		$exists_tags = array_flip(ArrayHelper::map(Tag::getTags($tags), 'id', 'name'));		
		
		foreach($tags as $tag) {
		
			$tag_id = 0;
			$tag = trim($tag);
			
			if (mb_strlen($tag) > 0) {
			
				//Check, if it's an existing tag
				if (isset($exists_tags[$tag]) && is_numeric($exists_tags[$tag])) {
					$tag_id = $exists_tags[$tag];
				} else {
					//new tag, so we need to create a tag
					if (!($tag_id = Tag::createTag($tag))) {
						$return = false;
						Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
						break;
					}
				}
				
				if ($tag_id > 0) {
				
					//Save the tag to the content
					if (!($return = ContentTag::saveContentTag($model_id, $tag_id))) {
						Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
						break;
					}
				}
			}	
		}
		unset($tag);				
		
		return $return;
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
