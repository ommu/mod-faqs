<?php
/**
 * FaqCategory
 * version: 0.0.1
 *
 * FaqCategory represents the model behind the search form about `app\modules\faq\models\FaqCategory`.
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 10:08 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\faq\models\FaqCategory as FaqCategoryModel;
//use app\coremodules\user\models\Users;

class FaqCategory extends FaqCategoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['cat_id', 'publish', 'parent', 'cat_name', 'cat_desc', 'orders', 'creation_id', 'modified_id'], 'integer'],
			[['creation_date', 'modified_date', 'parent_i','updated_date', 'slug', 'creation_search', 'modified_search', 'cat_name_i', 'cat_desc_i'], 'safe'],
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
		$query->joinWith(['creation creation', 'modified modified', 'name name', 'parents.name parents', 'description description']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['creation_search'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['parent_i'] = [
			'asc' => ['parents.message' => SORT_ASC],
			'desc' => ['parents.message' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['cat_name_i'] = [
			'asc' => ['name.message' => SORT_ASC],
			'desc' => ['name.message' => SORT_DESC],
		];
		$attributes['cat_desc_i'] = [
			'asc' => ['description.message' => SORT_ASC],
			'desc' => ['description.message' => SORT_DESC],
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
			't.cat_id' => isset($params['id']) ? $params['id'] : $this->cat_id,
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.parent' => $this->parent,
			't.cat_name' => $this->cat_name,
			't.cat_desc' => $this->cat_desc,
			't.orders' => $this->orders,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if(!isset($params['trash']))
			$query->andFilterWhere(['IN', 't.publish', [0,1]]);
		else
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);

		$query->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'creation.displayname', $this->creation_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search])
			->andFilterWhere(['like', 'name.message', $this->cat_name_i])
			->andFilterWhere(['like', 'description.message', $this->cat_desc_i])
			->andFilterWhere(['like', 'parents.message', $this->parent_i]);

		return $dataProvider;
	}
}
