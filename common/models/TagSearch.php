<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tag;

/**
 * TagSearch represents the model behind the search form about `common\models\Tag`.
 */
class TagSearch extends Tag
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['name', 'slug'], 'safe'],
		];
	}
	
	/**
	 * @override
	 */
	public function behaviors() {			
 		return [];
 	}	

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Tag::find()->where(['deleted_at' => null]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'deleted_at' => $this->deleted_at,
		]);
				
		$query->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'slug', $this->slug]);

		return $dataProvider;
	}
}
