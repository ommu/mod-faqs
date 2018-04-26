<?php
/**
 * LikeHistory
 * version: 0.0.1
 *
 * LikeHistory represents the model behind the search form about `app\modules\faq\models\LikeHistory`.
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 9 January 2018, 08:22 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\faq\models\LikeHistory as LikeHistoryModel;
//use app\modules\faq\models\FaqLikes;

class LikeHistory extends LikeHistoryModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id', 'publish', 'like_id'], 'integer'],
			[['likes_date', 'likes_ip', 'like_search'], 'safe'],
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
        $query = LikeHistoryModel::find()->alias('t');
		$query->joinWith(['like like']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $attributes = array_keys($this->getTableSchema()->columns);
        $attributes['like_search'] = [
            'asc' => ['like.like_id' => SORT_ASC],
            'desc' => ['like.like_id' => SORT_DESC],
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
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.like_id' => isset($params['like']) ? $params['like'] : $this->like_id,
			'cast(t.likes_date as date)' => $this->likes_date,
		]);

		if(!isset($params['trash']))
			$query->andFilterWhere(['IN', 't.publish', [0,1]]);
		else
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);

		$query->andFilterWhere(['like', 't.likes_ip', $this->likes_ip])
			->andFilterWhere(['like', 'like.like_id', $this->like_search]);

        return $dataProvider;
    }
}
