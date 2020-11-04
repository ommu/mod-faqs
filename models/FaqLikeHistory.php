<?php
/**
 * FaqLikeHistory
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 9 January 2018, 08:19 WIB
 * @modified date 27 April 2018, 06:56 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
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

namespace ommu\faq\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class FaqLikeHistory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	// Variable Search
	public $category_search;
	public $faq_search;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_like_history';
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
			'publish' => Yii::t('app', 'Like'),
			'like_id' => Yii::t('app', 'Like'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes IP'),
			'category_search' => Yii::t('app', 'Category'),
			'faq_search' => Yii::t('app', 'Faq'),
			'userDisplayname' => Yii::t('app', 'User'),
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
	 * {@inheritdoc}
	 * @return \ommu\faq\models\query\FaqLikeHistoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\faq\models\query\FaqLikeHistoryQuery(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['category_search'] = [
			'attribute' => 'category_search',
			'filter' => FaqCategory::getCategory(),
			'value' => function($model, $key, $index, $column) {
				return isset($model->like->faq->category) ? $model->like->faq->category->title->message : '-';
			},
			'visible' => !Yii::$app->request->get('like') && !Yii::$app->request->get('category') ? true : false,
		];
		$this->templateColumns['faq_search'] = [
			'attribute' => 'faq_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->like->faq->questionRltn) ? $model->like->faq->questionRltn->message : '-';
			},
			'visible' => !Yii::$app->request->get('like') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->like->user) ? $model->like->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('like') ? true : false,
		];
		$this->templateColumns['likes_date'] = [
			'attribute' => 'likes_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->likes_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'likes_date'),
		];
		$this->templateColumns['likes_ip'] = [
			'attribute' => 'likes_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->likes_ip;
			},
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

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
        if (parent::beforeValidate()) {
			$this->likes_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
