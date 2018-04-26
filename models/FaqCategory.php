<?php
/**
 * FaqCategory
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 4 January 2018, 16:24 WIB
 * @modified date 27 April 2018, 00:36 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_faq_category".
 *
 * The followings are the available columns in table "ommu_faq_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $parent_id
 * @property integer $cat_name
 * @property integer $cat_desc
 * @property integer $orders
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Faqs[] $faqs
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\behaviors\SluggableBehavior;
use app\models\SourceMessage;
use app\coremodules\user\models\Users;

class FaqCategory extends \app\components\ActiveRecord
{
	use \app\components\traits\GridViewSystem;
	use \app\components\traits\FileSystem;

	public $gridForbiddenColumn = ['modified_date','modified_search','updated_date','slug'];
	public $cat_name_i;
	public $cat_desc_i;

	// Variable Search
	public $parent_i;
	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_category';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class'	 => SluggableBehavior::className(),
				'attribute' => 'cat_name',
				'immutable' => true,
				'ensureUnique' => true,
			],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
		 [['publish', 'parent', 'cat_name', 'cat_desc', 'orders', 'creation_id', 'modified_id'], 'integer'],
			[['cat_name_i', 'cat_desc_i', 'orders'], 'required'],
			[['creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['slug'], 'string', 'max' => 32],
	  ];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFaqs()
	{
		return $this->hasMany(Faqs::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getName()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'cat_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'cat_desc']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParents()
	{
		return $this->hasOne(FaqCategory::className(), ['cat_id' => 'parent']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Cat'),
			'publish' => Yii::t('app', 'Publish'),
			'parent' => Yii::t('app', 'Parent'),
			'parent_i' => Yii::t('app', 'Parent'),
			'cat_name' => Yii::t('app', 'Cat Name'),
			'cat_desc' => Yii::t('app', 'Cat Desc'),
			'orders' => Yii::t('app', 'Orders'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
			'cat_name_i' => Yii::t('app', 'Category'),
			'cat_desc_i' => Yii::t('app', 'Cat Desc'),
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
		$this->templateColumns['parent_i'] = [
			'attribute' => 'parent_i',
			'value' => function($model, $key, $index, $column) {
				return $model->parent ? $model->parents->name->message : '-';
			},
		];
		$this->templateColumns['cat_name_i'] = [
			'attribute' => 'cat_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_name ? $model->name->message : '-';
			},
		];
		$this->templateColumns['cat_desc_i'] = [
			'attribute' => 'cat_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_desc ? $model->description->message : '-';
			},
		];
		$this->templateColumns['orders'] = 'orders';
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'creation_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->creation_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
			'format'	=> 'html',
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation->displayname) ? $model->creation->displayname : '-';
				},
			];
		}
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->updated_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
			'format'	=> 'html',
		];
		$this->templateColumns['slug'] = 'slug';
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => GridView::getFilterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id' => $model->primaryKey]);
					return GridView::getPublish($url, $model->publish);
				},
				'contentOptions' => ['class'=>'center'],
				'format'	=> 'raw',
			];
		}
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($publish = null) 
	{
		$items = [];
		$model = self::find();
		if($publish != null)
			$model = $model->andWhere(['publish' => $publish]);
		$model = $model->orderBy('cat_name ASC')->all();

		if($model !== null) {
			foreach($model as $val) {
				$items[$val->cat_id] = $val->name->message;
			}
		}
		
		return $items;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
				$this->modified_id = 0;
			}else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert) 
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);
		$location = Utility::getUrlTitle($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($this->isNewRecord || (!$this->isNewRecord && !$this->cat_name)) {
				$cat_name = new SourceMessage();
				$cat_name->location = $location.'_cat_name';
				$cat_name->message = $this->cat_name_i;
				if($cat_name->save())
					$this->cat_name = $cat_name->id;
				
			} else {
				$cat_name = SourceMessage::findOne($this->cat_name);
				$cat_name->message = $this->cat_name_i;
				$cat_name->save();
			}

			if($this->isNewRecord || (!$this->isNewRecord && !$this->cat_desc)) {
				$cat_desc = new SourceMessage();
				$cat_desc->location = $location.'_cat_desc';
				$cat_desc->message = $this->cat_desc_i;
				if($cat_desc->save())
					$this->cat_desc = $cat_desc->id;
				
			} else {
				$cat_desc = SourceMessage::findOne($this->cat_desc);
				$cat_desc->message = $this->cat_desc_i;
				$cat_desc->save();
			}
		}

		return true;	
	}

}
