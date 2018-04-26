<?php
/**
 * Helpful
 * version: 0.0.1
 *
 * Helpful represents the model behind the search form about `app\modules\faq\models\Helpful`.
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 9 January 2018, 08:35 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\faq\models\Helpful as HelpfulModel;
//use app\modules\faq\models\Faqs;
//use app\coremodules\user\models\Users;

class Helpful extends HelpfulModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id', 'faq_id', 'user_id', 'modified_id'], 'integer'],
			[['helpful', 'message', 'helpful_date', 'helpful_ip', 'modified_date', 'faq_search', 'user_search', 'modified_search'], 'safe'],
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
        $query = HelpfulModel::find()->alias('t');
		$query->joinWith(['faq faq', 'user user', 'modified modified']);

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
        $attributes['modified_search'] = [
            'asc' => ['modified.displayname' => SORT_ASC],
            'desc' => ['modified.displayname' => SORT_DESC],
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
			't.faq_id' => isset($params['faq']) ? $params['faq'] : $this->faq_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.helpful_date as date)' => $this->helpful_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 't.helpful', $this->helpful])
			->andFilterWhere(['like', 't.message', $this->message])
			->andFilterWhere(['like', 't.helpful_ip', $this->helpful_ip])
			->andFilterWhere(['like', 'faq.faq_id', $this->faq_search])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

        return $dataProvider;
    }
}
