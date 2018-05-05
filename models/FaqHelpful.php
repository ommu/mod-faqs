<?php
/**
 * FaqHelpful
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 9 January 2018, 08:31 WIB
 * @modified date 27 April 2018, 00:38 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_faq_helpful".
 *
 * The followings are the available columns in table "ommu_faq_helpful":
 * @property integer $id
 * @property integer $faq_id
 * @property integer $user_id
 * @property string $helpful
 * @property string $message
 * @property string $helpful_date
 * @property string $helpful_ip
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Faqs $faq
 * @property Users $user
 * @property Users $modified
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\user\models\Users;

class FaqHelpful extends \app\components\ActiveRecord
{
	use \ommu\traits\GridViewTrait;

	public $gridForbiddenColumn = ['message','helpful_ip','modified_date', 'modified_search'];

	// Variable Search
	public $category_search;
	public $faq_search;
	public $user_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_helpful';
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
			[['faq_id', 'helpful', 'message'], 'required'],
			[['faq_id', 'user_id', 'modified_id'], 'integer'],
			[['helpful', 'message'], 'string'],
			[['helpful_date', 'helpful_ip', 'modified_date'], 'safe'],
			[['helpful_ip'], 'string', 'max' => 20],
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
			'id' => Yii::t('app', 'ID'),
			'faq_id' => Yii::t('app', 'Faq'),
			'user_id' => Yii::t('app', 'User'),
			'helpful' => Yii::t('app', 'Helpful'),
			'message' => Yii::t('app', 'Message'),
			'helpful_date' => Yii::t('app', 'Helpful Date'),
			'helpful_ip' => Yii::t('app', 'Helpful Ip'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'category_search' => Yii::t('app', 'Category'),
			'faq_search' => Yii::t('app', 'Faq'),
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\query\FaqHelpfulQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\faq\models\query\FaqHelpfulQuery(get_called_class());
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
		$this->templateColumns['message'] = [
			'attribute' => 'message',
			'value' => function($model, $key, $index, $column) {
				return $model->message;
			},
		];
		$this->templateColumns['helpful_date'] = [
			'attribute' => 'helpful_date',
			'filter' => Html::input('date', 'helpful_date', Yii::$app->request->get('helpful_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->helpful_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->helpful_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['helpful_ip'] = [
			'attribute' => 'helpful_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->helpful_ip;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter' => Html::input('date', 'modified_date', Yii::$app->request->get('modified_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['helpful'] = [
			'attribute' => 'helpful',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->helpful == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
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

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			$this->helpful_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
