<?php
/**
 * FaqViews
 * version: 0.0.1
 *
 * FaqViews represents the model behind the search form about `app\modules\faq\models\FaqViews`.
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 15:17 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\faq\models\FaqViews as FaqViewsModel;
//use app\modules\faq\models\Faqs;
//use app\coremodules\user\models\Users;

class FaqViews extends FaqViewsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['view_id', 'publish', 'faq_id', 'user_id', 'views'], 'integer'],
			[['view_date', 'view_ip', 'deleted_date', 'faq_search', 'user_search'], 'safe'],
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
        $query = FaqViewsModel::find()->alias('t');
		$query->joinWith(['faq faq', 'user user']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $attributes = array_keys($this->getTableSchema()->columns);
        $attributes['faq_search'] = [
            'asc' => ['faq.faq_id' => SORT_ASC],
            'desc' => ['faq.faq_id' => SORT_DESC],
        ];
        $attributes['user_search'] = [
            'asc' => ['user.displayname' => SORT_ASC],
            'desc' => ['user.displayname' => SORT_DESC],
        ];
        $dataProvider->setSort([
            'attributes' => $attributes,
            'defaultOrder' => ['view_id' => SORT_DESC],
        ]);

        $this->load($params);

        if(!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
			't.view_id' => isset($params['id']) ? $params['id'] : $this->view_id,
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.faq_id' => isset($params['faq']) ? $params['faq'] : $this->faq_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.views' => $this->views,
			'cast(t.view_date as date)' => $this->view_date,
			'cast(t.deleted_date as date)' => $this->deleted_date,
		]);

		if(!isset($params['trash']))
			$query->andFilterWhere(['IN', 't.publish', [0,1]]);
		else
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'faq.faq_id', $this->faq_search])
			->andFilterWhere(['like', 'user.displayname', $this->user_search]);

        return $dataProvider;
    }
}
