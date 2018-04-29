<?php
/**
 * FaqLikeHistory
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 9 January 2018, 08:19 WIB
 * @modified date 27 April 2018, 06:56 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_faq_like_history".
 *
 * The followings are the available columns in table "ommu_faq_like_history":
 * @property integer $id
 * @property integer $publish
 * @property integer $like_id
 * @property string $likes_date
 * @property string $likes_ip
 *
 * The followings are the available model relations:
 * @property FaqLikes $like
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class FaqLikeHistory extends \app\components\ActiveRecord
{
	use \app\components\traits\GridViewSystem;

	public $gridForbiddenColumn = [];

	// Variable Search
	public $category_search;
	public $faq_search;
	public $user_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_like_history';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'like_id', 'likes_ip'], 'required'],
			[['publish', 'like_id'], 'integer'],
			[['likes_date'], 'safe'],
			[['likes_ip'], 'string', 'max' => 20],
			[['like_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqLikes::className(), 'targetAttribute' => ['like_id' => 'like_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'like_id' => Yii::t('app', 'Like'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes Ip'),
			'category_search' => Yii::t('app', 'Category'),
			'faq_search' => Yii::t('app', 'Faq'),
			'user_search' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLike()
	{
		return $this->hasOne(FaqLikes::className(), ['like_id' => 'like_id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\query\FaqLikeHistoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\faq\models\query\FaqLikeHistoryQuery(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('like')) {
			if(!Yii::$app->request->get('category')) {
				$this->templateColumns['category_search'] = [
					'attribute' => 'category_search',
					'filter' => FaqCategory::getCategory(),
					'value' => function($model, $key, $index, $column) {
						return isset($model->like->faq->category) ? $model->like->faq->category->title->message : '-';
					},
				];
			}
			$this->templateColumns['faq_search'] = [
				'attribute' => 'faq_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->like->faq->questionRltn) ? $model->like->faq->questionRltn->message : '-';
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->like->user) ? $model->like->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['likes_date'] = [
			'attribute' => 'likes_date',
			'filter' => Html::input('date', 'likes_date', Yii::$app->request->get('likes_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->likes_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->likes_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['likes_ip'] = [
			'attribute' => 'likes_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->likes_ip;
			},
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id' => $model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'contentOptions' => ['class'=>'center'],
				'format'	=> 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
