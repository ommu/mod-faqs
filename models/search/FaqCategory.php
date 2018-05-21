<?php
/**
 * FaqCategory
 *
 * FaqCategory represents the model behind the search form about `ommu\faq\models\FaqCategory`.
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 5 January 2018, 10:08 WIB
 * @modified date 27 April 2018, 12:54 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\faq\models\FaqCategory as FaqCategoryModel;

class FaqCategory extends FaqCategoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['cat_id', 'publish', 'parent_id', 'cat_name', 'cat_desc', 'creation_id', 'modified_id'], 'integer'],
			[['orders', 'creation_date', 'modified_date', 'updated_date', 'slug',
				'cat_name_i', 'cat_desc_i', 'creation_search', 'modified_search'], 'safe'],
		];
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
	public function search($params)
	{
		$query = FaqCategoryModel::find()->alias('t');
		$query->joinWith([
			'view view', 
			'title title', 
			'description description', 
			'creation creation', 
			'modified modified', 
			'parent.title parent'
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['cat_name_i'] = [
			'asc' => ['title.message' => SORT_ASC],
			'desc' => ['title.message' => SORT_DESC],
		];
		$attributes['cat_desc_i'] = [
			'asc' => ['description.message' => SORT_ASC],
			'desc' => ['description.message' => SORT_DESC],
		];
		$attributes['creation_search'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['parent_id'] = [
			'asc' => ['parent.message' => SORT_ASC],
			'desc' => ['parent.message' => SORT_DESC],
		];
		$attributes['faq_search'] = [
			'asc' => ['view.faqs' => SORT_ASC],
			'desc' => ['view.faqs' => SORT_DESC],
		];
		$attributes['faq_all_search'] = [
			'asc' => ['view.faq_all' => SORT_ASC],
			'desc' => ['view.faq_all' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['cat_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.cat_id' => $this->cat_id,
			't.parent_id' => isset($params['parent']) ? $params['parent'] : $this->parent_id,
			't.cat_name' => $this->cat_name,
			't.cat_desc' => $this->cat_desc,
			't.orders' => $this->orders,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'title.message', $this->cat_name_i])
			->andFilterWhere(['like', 'description.message', $this->cat_desc_i])
			->andFilterWhere(['like', 'creation.displayname', $this->creation_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
