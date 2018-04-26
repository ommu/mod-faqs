<?php
/**
 * Faqs
 * version: 0.0.1
 *
 * Faqs represents the model behind the search form about `app\modules\faq\models\Faqs`.
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 16:52 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\faq\models\Faqs as FaqsModel;
//use app\modules\faq\models\FaqCategory;
//use app\coremodules\user\models\Users;

class Faqs extends FaqsModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['faq_id', 'publish', 'cat_id', 'question', 'answer', 'orders', 'creation_id', 'modified_id'], 'integer'],
			[['creation_date', 'modified_date', 'updated_date', 'slug', 'cat_name_i', 'creation_search', 'question_i','answer_i', 'modified_search'], 'safe'],
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
		$query = FaqsModel::find()->alias('t');
		$query->joinWith(['category category', 'creation creation', 'modified modified', 'questions question_relation', 'answers answer_relation']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['question_i'] = [
			'asc' => ['question_relation.message' => SORT_ASC],
			'desc' => ['question_relation.message' => SORT_DESC],
		];
		$attributes['answer_i'] = [
			'asc' => ['answer_relation.message' => SORT_ASC],
			'desc' => ['answer_relation.message' => SORT_DESC],
		];
		$attributes['creation_search'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['faq_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.faq_id' => isset($params['id']) ? $params['id'] : $this->faq_id,
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.question' => $this->question,
			't.answer' => $this->answer,
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
			->andFilterWhere(['like', 'question_relation.message', $this->question_i])
			->andFilterWhere(['like', 'answer_relation.message', $this->answer_i]);

		return $dataProvider;
	}
}
