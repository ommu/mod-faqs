<?php
namespace app\modules\faq\controllers\v1;

use Yii;
use app\components\api\ActiveController;
use app\modules\faq\models\FaqCategory;
use app\modules\faq\models\Faqs;
use yii\data\ActiveDataProvider;
use app\models\CoreZoneDistrict;
use app\models\search\CoreZoneDistrict as CoreZoneDistrictSearch;
use app\models\view\CoreZoneDistrict as CoreZoneDistrictView;
use app\models\SourceMessage;


/**
 * faqsController
 * version: 0.0.1
 *
 * @copyright Copyright(c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link    http://ecc.ft.ugm.ac.id
 * @author  Eko Hariyanto <haryeko29@gmail.com>
 * @created 4 May 2018, 13:24 WIB
 * @contact (+62)857-4381-4273
 *
 */
class FaqsController extends ActiveController
{
	public $modelClass = 'app\modules\faq\models\Faqs';
	public $searchModelClass = 'app\modules\faq\models\search\Faqs';
	public static $authType = 0;

	public function actionQuestion() {
		//merubah data ke array
		$faq = [];
		$model = Faqs::find()
				->where(['publish' => 1])
				//->AndWhere(['parent_id' => 0])
				->all();
		$i=0;
		foreach ($model as $key ) {
			$faq[$i]['id'] = $key->faq_id;
			$faq[$i]['question'] = $key->questionRltn->message;
			$i++;
		}
		return $faq;
	}

	public function actionSearch()
	{
		if (!empty($_GET)) {
        $model = new $this->modelClass;
        try {
		$array= [];
				//$model = new $this->modelClass;
						$provider = new ActiveDataProvider([
								'query' => $model->find()
								->select(['ommu_faqs.faq_id', 'ommu_faqs.question', 'ommu_faqs.answer'])
								->leftJoin('ommu_faq_category', 'ommu_faqs.cat_id=ommu_faq_category.cat_id')
								->where(['ommu_faq_category.publish' => 1])
								->where(['ommu_faqs.publish' => 1])
								->andFilterWhere(['or',
									['like', 'ommu_faq_category.parent_id', $_GET['parent_id']],
									['like', 'ommu_faq_category.cat_name', $_GET['cat_name']],
								]),
								'pagination' => false
							]);
						$data = $provider->getModels();
						$loop = 0;
						foreach ($data as $key) {
							$array[$loop]['data'] = $key->faq_id;
							$array[$loop]['question'] = $key->questionRltn->message;
							$array[$loop]['answer'] = $key->answerRltn->message;
							$loop++;
						}
						$array['parent'] = $_GET['parent_id'];
						$array['category'] = $_GET['cat_name'];
				//return $array;
				 } catch (Exception $ex) {
            throw new \yii\web\HttpException(500, 'Internal server error');
        }

        if ($provider->getCount() <= 0) {
            throw new \yii\web\HttpException(404, 'No entries found with this query string');
        } else {
            return $array;
        }
    } else {
        throw new \yii\web\HttpException(400, 'There are no query string');
    }
	}
	public function actionGetQuestion()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$term = Yii::$app->request->get('term');
		//$cityId = Yii::$app->request->get('cid', null);
		$extend = Yii::$app->request->get('extend');
		
		$model = Faqs::find()->alias('t');
		/*if($extend == null)
			$model->where(['like', 't.district_name', $term]);
		else {*/
			$model->leftJoin(['view' => SourceMessage::tableName()], 't.question=view.id');
			$model->andFilterWhere(['like', 'view.message', $term]);
			//$model->where(['like', 't.question', $term]);
		//}
		//if($cityId != null)
			//$model->andWhere(['t.city_id' => $cityId]);
		$model = $model->published()->limit(10)->all();

		$result = [];
		$i = 0;
		foreach($model as $val) {
			if($extend == null) {
				$result[] = [
					'label' => $val->questionRltn->message, 
					'value' => $val->faq_id,
				];
			} else {
				$i++;
				//$extendArray = array_map("trim", explode(',', $extend));
				$result[$i] = [
					'label' => $val->questionRltn->message, 
					'value' => $val->faq_id,
				];
					$result[$i]['question'] =  $val->questionRltn->message;
			}
		}
		return $result;
	}
	 public function actionLookup() {
        //get search string from the autocomplete
        $term = $_GET['term'];
        //define the static data pool
        $data = array(
            'Arya Stark',
            'Cersei Lannister',
            'Daenarys Targaryen',
            'Robb Stark',
            'Bran Stark',
            'Rickard Karstark',
            'Tyrion Lannister',
        );
        //filter the data
        $return = array_values(array_filter($data, function($element) use ($term) {
                    return (stripos($element, $term) !== false);
                }));
        //return as
        echo CJSON::encode($return);
    }

}
