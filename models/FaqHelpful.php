<?php
/**
 * FaqHelpful
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 9 January 2018, 08:31 WIB
 * @modified date 27 April 2018, 00:38 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
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

namespace ommu\faq\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class FaqHelpful extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['message', 'helpful_ip', 'modified_date', 'modifiedDisplayname'];

	// Variable Search
	public $category_search;
	public $faq_search;
	public $userDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_helpful';
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
			'helpful_ip' => Yii::t('app', 'Helpful IP'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'category_search' => Yii::t('app', 'Category'),
			'faq_search' => Yii::t('app', 'Faq'),
			'userDisplayname' => Yii::t('app', 'User'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
	 * {@inheritdoc}
	 * @return \ommu\faq\models\query\FaqHelpfulQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\faq\models\query\FaqHelpfulQuery(get_called_class());
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
				return isset($model->faq->category) ? $model->faq->category->title->message : '-';
			},
			'visible' => !Yii::$app->request->get('category') && !Yii::$app->request->get('faq') ? true : false,
		];
		$this->templateColumns['faq_search'] = [
			'attribute' => 'faq_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->faq->questionRltn) ? $model->faq->questionRltn->message : '-';
			},
			'visible' => !Yii::$app->request->get('faq') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['message'] = [
			'attribute' => 'message',
			'value' => function($model, $key, $index, $column) {
				return $model->message;
			},
		];
		$this->templateColumns['helpful_date'] = [
			'attribute' => 'helpful_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->helpful_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'helpful_date'),
		];
		$this->templateColumns['helpful_ip'] = [
			'attribute' => 'helpful_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->helpful_ip;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['helpful'] = [
			'attribute' => 'helpful',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->helpful);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
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
            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }

            $this->helpful_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
