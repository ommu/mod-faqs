<?php
/**
 * Faqs
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 April 2018, 00:33 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_faqs".
 *
 * The followings are the available columns in table "_faqs":
 * @property integer $faq_id
 * @property integer $helpfuls
 * @property string $views
 * @property string $view_all
 * @property string $likes
 * @property integer $like_all
 *
 */

namespace ommu\faq\models\view;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Faqs extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_faqs';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['faq_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['faq_id', 'helpfuls', 'like_all'], 'integer'],
			[['views', 'view_all', 'likes'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'faq_id' => Yii::t('app', 'Faq'),
			'helpfuls' => Yii::t('app', 'Helpfuls'),
			'views' => Yii::t('app', 'Views'),
			'view_all' => Yii::t('app', 'View All'),
			'likes' => Yii::t('app', 'Likes'),
			'like_all' => Yii::t('app', 'Like All'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['faq_id'] = [
			'attribute' => 'faq_id',
			'value' => function($model, $key, $index, $column) {
				return $model->faq_id;
			},
		];
		$this->templateColumns['helpfuls'] = [
			'attribute' => 'helpfuls',
			'value' => function($model, $key, $index, $column) {
				return $model->helpfuls;
			},
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				return $model->views;
			},
		];
		$this->templateColumns['view_all'] = [
			'attribute' => 'view_all',
			'value' => function($model, $key, $index, $column) {
				return $model->view_all;
			},
		];
		$this->templateColumns['likes'] = [
			'attribute' => 'likes',
			'value' => function($model, $key, $index, $column) {
				return $model->likes;
			},
		];
		$this->templateColumns['like_all'] = [
			'attribute' => 'like_all',
			'value' => function($model, $key, $index, $column) {
				return $model->like_all;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['faq_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
