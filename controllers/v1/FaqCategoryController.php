<?php
namespace app\modules\faq\controllers\v1;

use Yii;
use app\components\api\ActiveController;
use app\modules\faq\models\FaqCategory;

/**
 * faqCategoryController
 * version: 0.0.1
 *
 * @copyright Copyright(c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link    http://ecc.ft.ugm.ac.id
 * @author  Eko Hariyanto <haryeko29@gmail.com>
 * @created 3 May 2018, 14:23 WIB
 * @contact (+62)857-4381-4273
 *
 */
class FaqCategoryController extends ActiveController
{
	public $modelClass = 'app\modules\faq\models\FaqCategory';
	public $searchModelClass = 'app\modules\faq\models\search\FaqCategory';
	public static $authType = 0;

	public function actionGetcategory() {
		//merubah data ke array
		$subject_category = [];
		$model = FaqCategory::find()
				->where(['publish' => 1])
				->AndWhere(['parent_id' => 0])
				->all();
		//$meta = CoreMeta::find()->one();
		$i=0;
		
		//$subject_category['cat']= $model-;
		foreach ($model as $key ) {
			 //$contact_category[$i] = $key; 
			$sub = [];
			$subject_category[$i]['parent_id'] = $key->parent_id; 
			$subject_category[$i]['cat_id'] = $key->cat_id; 
			$subject_category[$i]['cat_name'] = $key->cat_name; 
			$subject_category[$i]['cat_desc'] = $key->cat_desc;
			$subject_category[$i]['category'] = $key->title->message; 
			$mode = FaqCategory::find()
				->where(['publish' => 1])
				->AndWhere(['like', 'parent_id', $key->cat_id])
				->all();
				$a=0;
				foreach ($mode as $k) {
					$sub[$a]['parent'] = $k->parent_id;
					$sub[$a]['sub_id'] = $k->cat_name;
					$sub[$a]['sub'] = $k->title->message;
					$a++;
				}
					$subject_category[$i]['sub menu'] = $sub; 
			$subject_category[$i]['cat_desc'] = $key->cat_desc; 
			$subject_category[$i]['desc'] = $key->description->message;
			 $i++;
		}
		return $subject_category;
	}
	 
}
