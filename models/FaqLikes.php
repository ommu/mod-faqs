<?php
/**
 * FaqLikes
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 8 January 2018, 16:52 WIB
 * @modified date 27 April 2018, 00:38 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_faq_likes".
 *
 * The followings are the available columns in table "ommu_faq_likes":
 * @property integer $like_id
 * @property integer $publish
 * @property integer $faq_id
 * @property integer $user_id
 * @property string $likes_date
 * @property string $likes_ip
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property FaqLikeHistory[] $histories
 * @property Faqs $faq
 * @property Users $user
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\coremodules\user\models\Users;

class FaqLikes extends \app\components\ActiveRecord
{
	use \app\components\traits\GridViewSystem;

	public $gridForbiddenColumn = ['likes_ip','updated_date'];

	// Variable Search
	public $category_search;
	public $faq_search;
	public $user_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_likes';
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
			[['faq_id', 'likes_ip'], 'required'],
			[['publish', 'faq_id', 'user_id'], 'integer'],
			[['likes_date', 'updated_date'], 'safe'],
			[['likes_ip'], 'string', 'max' => 20],
			[['faq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faqs::className(), 'targetAttribute' => ['faq_id' => 'faq_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'like_id' => Yii::t('app', 'Like'),
			'publish' => Yii::t('app', 'Publish'),
			'faq_id' => Yii::t('app', 'Faq'),
			'user_id' => Yii::t('app', 'User'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes Ip'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'category_search' => Yii::t('app', 'Category'),
			'faq_search' => Yii::t('app', 'Faq'),
			'user_search' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(FaqLikeHistory::className(), ['like_id' => 'like_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFaq()
	{
		return $this->hasOne(Faqs::className(), ['faq_id' => 'faq_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\query\FaqLikesQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\faq\models\query\FaqLikesQuery(get_called_class());
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
		if(!Yii::$app->request->get('category') && !Yii::$app->request->get('faq')) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'filter' => FaqCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->faq->category) ? $model->faq->category->title->message : '-';
				},
			];
		}
		if(!Yii::$app->request->get('faq')) {
			$this->templateColumns['faq_search'] = [
				'attribute' => 'faq_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->faq->questionRltn) ? $model->faq->questionRltn->message : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter' => Html::input('date', 'updated_date', Yii::$app->request->get('updated_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format' => 'html',
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
				->where(['like_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			$this->likes_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

}
