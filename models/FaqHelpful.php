<?php
/**
 * FaqHelpful
 * version: 0.0.1
 *
 * This is the model class for table "ommu_faq_helpful".
 *
 * The followings are the available columns in table "ommu_faq_helpful":
 * @property string $id
 * @property string $faq_id
 * @property string $user_id
 * @property string $helpful
 * @property string $message
 * @property string $helpful_date
 * @property string $helpful_ip
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property Faqs $faq

 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 9 January 2018, 08:31 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;
use app\libraries\grid\GridView;

class FaqHelpful extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['modified_date', 'modified_id'];

	// Variable Search
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
		 [['faq_id', 'user_id', 'helpful', 'message', 'helpful_ip', 'modified_id'], 'required'],
			[['faq_id', 'user_id', 'modified_id'], 'integer'],
			[['helpful', 'message'], 'string'],
			[['helpful_date', 'modified_date'], 'safe'],
			[['helpful_ip'], 'string', 'max' => 20],
			[['faq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faqs::className(), 'targetAttribute' => ['faq_id' => 'faq_id']],
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
			'faq_search' => Yii::t('app', 'Faq'),
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
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
		if(!Yii::$app->request->get('faq')) {
			$this->templateColumns['faq_search'] = [
				'attribute' => 'faq_search',
				'value' => function($model, $key, $index, $column) {
					return $model->faq->faq_id;
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user->displayname) ? $model->user->displayname : '-';
				},
			];
		}
		//$this->templateColumns['helpful'] = 'helpful';
		$this->templateColumns['helpful'] = [
			'attribute' => 'helpful',
			// 'filter'=>array(0=>'Unpaid', 1=>'Paid'),
			'filter' => GridView::getFilterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['helpful', 'id' => $model->primaryKey]);
				// return $model->status ? Html::a(Yii::t('app', 'Paid'), $url, ['data-method' => 'post',]) : Html::a(Yii::t('app', 'Unpaid'), $url, ['data-method' => 'post',]);
				// return self::getPaid($url, $model->status);
				return $model->helpful ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
			'format'	=> 'raw',
		];
		$this->templateColumns['message'] = 'message';
		$this->templateColumns['helpful_date'] = [
			'attribute' => 'helpful_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'helpful_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->helpful_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->helpful_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
			'format'	=> 'html',
		];
		$this->templateColumns['helpful_ip'] = 'helpful_ip';
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->modified_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
			'format'	=> 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified->displayname) ? $model->modified->displayname : '-';
				},
			];
		}
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		}
		return true;
	}

}
