<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property PostTag[] $postTags
 */
class Tag extends \yii\db\ActiveRecord
{

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
		return 'tag';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name','slug'], 'required'],
			[['name', 'slug'], 'string', 'max' => 255],
			['name', 'unique', 'filter' => ['deleted_at' => null]],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('app/tag', 'Name'),
			'slug' => Yii::t('app/tag', 'Slug'),
			'created_at' => Yii::t('app', 'Created at'),
			'updated_at' => Yii::t('app', 'Updated at'),
		];
	}
	
	/**
	 *
	 *	Get the tags whith like 
	 *	
	 * @return \yii\db\ActiveQuery
	 */
	public function getTagsWithLike($name = '', $as = '')
	{
    	return Tag::find()
			->select(['id', 'name' . (!empty($as) ? ' AS '.$as : '')])
			->where(['deleted_at' => null,])
			->andFilterWhere(['like', 'name', $name])
			->asArray()
			->all();
	}	

	/**
	 *
	 *	Get the existing tags
	 *	 	
	 * @return \yii\db\ActiveQuery
	 */
	
	public function getTags($name = array()) {
		return Tag::find()
			->select(['id', 'name'])
			->where(['deleted_at' => null])
			->andFilterWhere(['in', 'name', $name])
			->asArray()
			->all();		
	}
	
	/**
	 * Create a Tag
	 * @parameter name
	 * @return mixed
	 */
	public function createTag($name)
	{
		$tag = new Tag();
		$tag->name = $name;
		$tag->slug = $name;
	
		if ($tag->save()) {
			return $tag->id;
		} else {
			return false;
		}
	} 			

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getContentTags()
	{
		return $this->hasMany(ContentTag::className(), ['tag_id' => 'id']);
	}
	
	
}
