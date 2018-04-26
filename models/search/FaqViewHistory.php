<?php
/**
 * FaqViewHistory
 * version: 0.0.1
 *
 * FaqViewHistory represents the model behind the search form about `app\modules\faq\models\FaqViewHistory`.
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 8 January 2018, 15:19 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\faq\models\FaqViewHistory as FaqViewHistoryModel;
//use app\modules\faq\models\FaqViews;

class FaqViewHistory extends FaqViewHistoryModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id', 'view_id'], 'integer'],
			[['view_date', 'view_ip', 'view_search'], 'safe'],
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
        $query = FaqViewHistoryModel::find()->alias('t');
		$query->joinWith(['view view']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $attributes = array_keys($this->getTableSchema()->columns);
        $attributes['view_search'] = [
            'asc' => ['view.view_id' => SORT_ASC],
            'desc' => ['view.view_id' => SORT_DESC],
        ];
        $dataProvider->setSort([
            'attributes' => $attributes,
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $this->load($params);

        if(!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
			't.id' => isset($params['id']) ? $params['id'] : $this->id,
			't.view_id' => isset($params['view']) ? $params['view'] : $this->view_id,
			'cast(t.view_date as date)' => $this->view_date,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'view.view_id', $this->view_search]);

        return $dataProvider;
    }
}
