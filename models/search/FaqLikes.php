<?php
/**
 * FaqLikes
 *
 * FaqLikes represents the model behind the search form about `ommu\faq\models\FaqLikes`.
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 8 January 2018, 16:53 WIB
 * @modified date 29 April 2018, 19:23 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @link https://github.com/ommu/mod-faqs
 *
 */

namespace ommu\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\faq\models\FaqLikes as FaqLikesModel;

class FaqLikes extends FaqLikesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['like_id', 'publish', 'faq_id', 'user_id'], 'integer'],
			[['likes_date', 'likes_ip', 'updated_date',
				'category_search', 'faq_search', 'user_search'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
		if(!($column && is_array($column)))
			$query = FaqLikesModel::find()->alias('t');
		else
			$query = FaqLikesModel::find()->alias('t')->select($column);
		$query->joinWith([
			'faq faq', 
			'faq.questionRltn questionRltn', 
			'faq.category.title category', 
			'user user',
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['category_search'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['faq_search'] = [
			'asc' => ['questionRltn.message' => SORT_ASC],
			'desc' => ['questionRltn.message' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['like_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.like_id' => $this->like_id,
			't.faq_id' => isset($params['faq']) ? $params['faq'] : $this->faq_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.likes_date as date)' => $this->likes_date,
			'cast(t.updated_date as date)' => $this->updated_date,
			'faq.cat_id' => isset($params['category']) ? $params['category'] : $this->category_search,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.likes_ip', $this->likes_ip])
			->andFilterWhere(['like', 'questionRltn.message', $this->faq_search])
			->andFilterWhere(['like', 'user.displayname', $this->user_search]);

		return $dataProvider;
	}
}
