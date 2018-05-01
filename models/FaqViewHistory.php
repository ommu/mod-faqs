<?php
/**
 * FaqViewHistory
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 5 January 2018, 16:48 WIB
 * @modified date 27 April 2018, 06:57 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_faq_view_history".
 *
 * The followings are the available columns in table "ommu_faq_view_history":
 * @property integer $id
 * @property integer $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property FaqViews $view
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class FaqViewHistory extends \app\components\ActiveRecord
{
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
		return 'ommu_faq_view_history';
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
			[['view_id', 'view_ip'], 'required'],
			[['view_id'], 'integer'],
			[['view_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqViews::className(), 'targetAttribute' => ['view_id' => 'view_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'view_id' => Yii::t('app', 'View'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View Ip'),
			'category_search' => Yii::t('app', 'Category'),
			'faq_search' => Yii::t('app', 'Faq'),
			'user_search' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(FaqViews::className(), ['view_id' => 'view_id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\query\FaqViewHistoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\faq\models\query\FaqViewHistoryQuery(get_called_class());
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
		if(!Yii::$app->request->get('view')) {
			if(!Yii::$app->request->get('category')) {
				$this->templateColumns['category_search'] = [
					'attribute' => 'category_search',
					'filter' => FaqCategory::getCategory(),
					'value' => function($model, $key, $index, $column) {
						return isset($model->view->faq->category) ? $model->view->faq->category->title->message : '-';
					},
				];
			}
			$this->templateColumns['faq_search'] = [
				'attribute' => 'faq_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->view->faq->questionRltn) ? $model->view->faq->questionRltn->message : '-';
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->view->user) ? $model->view->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'filter' => Html::input('date', 'view_date', Yii::$app->request->get('view_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->view_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->view_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['view_ip'] = [
			'attribute' => 'view_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->view_ip;
			},
		];
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

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			$this->view_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
