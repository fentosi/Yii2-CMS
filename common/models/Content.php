<?php

namespace common\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Expression;

use common\models\ContentTag;



/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $lead
 * @property string $content
 * @property string $seo_title
 * @property string $seo_meta_desc
 * @property integer $comment
 * @property integer $user_id
 * @property string $status
 * @property string $created_at
 * @property string $published_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property User $user
 */
class Content extends \yii\db\ActiveRecord
{

	public $tags;

	/**
	 * @inheritdoc
	 */
	public static function getContentStatuses()
	{
		return [
			'public' => Yii::t('app/content', 'Public'),
			'private' => Yii::t('app/content', 'Private'),
			'protected' => Yii::t('app/content', 'Protected'),
		];
	}	

	/**
	 * @inheritdoc
	 */
	public static function getCommentStatuses()
	{
		return [
			0 => Yii::t('app', 'Close'),
			1 => Yii::t('app', 'Open'),
		];	
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
				[
					'class' => SluggableBehavior::className(),
					'attribute' => 'slug',
					'slugAttribute' => 'slug',
					'ensureUnique' => true,
					'uniqueValidator' => [
						'filter' => [
							'deleted_at' => null,
						],
					],
				],
			];			
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'content';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'lead', 'user_id'], 'required'],
			[['lead', 'content', 'status', 'tags'], 'string'],
			[['comment', 'user_id'], 'integer'],
			[['created_at', 'published_at', 'updated_at', 'deleted_at', 'tags'], 'safe'],
			[['title', 'slug', 'seo_title', 'seo_meta_desc'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app/content', 'ID'),
			'title' => Yii::t('app/content', 'Title'),
			'slug' => Yii::t('app/content', 'Slug'),
			'lead' => Yii::t('app/content', 'Lead'),
			'content' => Yii::t('app/content', 'Content'),
			'seo_title' => Yii::t('app/content', 'Seo Title'),
			'seo_meta_desc' => Yii::t('app/content', 'Seo Meta Desc'),
			'comment' => Yii::t('app/content', 'Comment'),
			'user_id' => Yii::t('app/content', 'User ID'),
			'status' => Yii::t('app/content', 'Status'),
			'tags' => Yii::t('app/tag', 'Tags'),
			'created_at' => Yii::t('app/content', 'Created At'),
			'published_at' => Yii::t('app/content', 'Published At'),
			'updated_at' => Yii::t('app/content', 'Updated At'),
			'deleted_at' => Yii::t('app/content', 'Deleted At'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		 return $this->hasOne(User::className(), ['id' => 'user_id']);

	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags()
	{
	
		return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
				->viaTable('content_tag', ['content_id' => 'id'])
				->select('tag.id, name')
				->all();
	}
	
	/**
	 * Save the content with tags		
	 * @return boolean
	 */	
	public function saveContent() {
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();			

		try {
			
			$save = true;
						
			if ($this->save()) {					
				//delete all the tags related to the content from content_tag table 
				//save all the tags to the content_tag table
				if (!(contentTag::deleteContentTag($this->id) !== false && $save = contentTag::saveAllContentTag($this->id, $this->tags))) {
					Yii::$app->getSession()->setFlash('error', 'There is an error while saving the data');
				}
			}
			
			if ($save !== false) {
				$transaction->commit();
			} 
				
		} catch(Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
		
		return $save;	
		
	}
	
}
