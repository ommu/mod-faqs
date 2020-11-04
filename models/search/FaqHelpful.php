<?php
/**
 * FaqHelpful
 *
 * FaqHelpful represents the model behind the search form about `ommu\faq\models\FaqHelpful`.
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 9 January 2018, 08:35 WIB
 * @modified date 30 April 2018, 09:07 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
 *
 */

namespace ommu\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\faq\models\FaqHelpful as FaqHelpfulModel;

class FaqHelpful extends FaqHelpfulModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'faq_id', 'user_id', 'modified_id'], 'integer'],
			[['helpful', 'message', 'helpful_date', 'helpful_ip', 'modified_date',
				'category_search', 'faq_search', 'userDisplayname', 'modifiedDisplayname'], 'safe'],
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
        if (!($column && is_array($column))) {
            $query = FaqHelpfulModel::find()->alias('t');
        } else {
            $query = FaqHelpfulModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'faq faq', 
			'faq.questionRltn questionRltn', 
			'faq.category.title category', 
			'user user',
			'modified modified'
		])
		->groupBy(['id']);

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
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.faq_id' => isset($params['faq']) ? $params['faq'] : $this->faq_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.helpful' => $this->helpful,
			'cast(t.helpful_date as date)' => $this->helpful_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'faq.cat_id' => isset($params['category']) ? $params['category'] : $this->category_search,
		]);

		$query->andFilterWhere(['like', 't.message', $this->message])
			->andFilterWhere(['like', 't.helpful_ip', $this->helpful_ip])
			->andFilterWhere(['like', 'questionRltn.message', $this->faq_search])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
