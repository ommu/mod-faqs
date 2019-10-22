<?php
/**
 * FaqCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 April 2018, 00:32 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_faq_category".
 *
 * The followings are the available columns in table "_faq_category":
 * @property integer $cat_id
 * @property string $faqs
 * @property integer $faq_all
 *
 */

namespace ommu\faq\models\view;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class FaqCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_faq_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['cat_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'faq_all'], 'integer'],
			[['faqs'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'faqs' => Yii::t('app', 'Faqs'),
			'faq_all' => Yii::t('app', 'Faq All'),
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

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_id;
			},
		];
		$this->templateColumns['faqs'] = [
			'attribute' => 'faqs',
			'value' => function($model, $key, $index, $column) {
				return $model->faqs;
			},
		];
		$this->templateColumns['faq_all'] = [
			'attribute' => 'faq_all',
			'value' => function($model, $key, $index, $column) {
				return $model->faq_all;
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
			$model = $model->where(['cat_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
