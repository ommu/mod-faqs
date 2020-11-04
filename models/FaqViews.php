<?php
/**
 * FaqViews
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 5 January 2018, 15:05 WIB
 * @modified date 27 April 2018, 00:37 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
 *
 * This is the model class for table "ommu_faq_views".
 *
 * The followings are the available columns in table "ommu_faq_views":
 * @property integer $view_id
 * @property integer $publish
 * @property integer $faq_id
 * @property integer $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 * @property string $deleted_date
 *
 * The followings are the available model relations:
 * @property FaqViewHistory[] $histories
 * @property Faqs $faq
 * @property Users $user
 *
 */

namespace ommu\faq\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class FaqViews extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['view_ip', 'deleted_date'];

	// Variable Search
	public $category_search;
	public $faq_search;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_views';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['faq_id'], 'required'],
			[['publish', 'faq_id', 'user_id', 'views'], 'integer'],
			[['user_id', 'view_date', 'view_ip', 'deleted_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
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
			'view_id' => Yii::t('app', 'View'),
			'publish' => Yii::t('app', 'Publish'),
			'faq_id' => Yii::t('app', 'Faq'),
			'user_id' => Yii::t('app', 'User'),
			'views' => Yii::t('app', 'Views'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
			'deleted_date' => Yii::t('app', 'Deleted Date'),
			'category_search' => Yii::t('app', 'Category'),
			'faq_search' => Yii::t('app', 'Faq'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(FaqViewHistory::className(), ['view_id' => 'view_id']);
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
	 * {@inheritdoc}
	 * @return \ommu\faq\models\query\FaqViewsQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\faq\models\query\FaqViewsQuery(get_called_class());
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
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = [
			'attribute' => 'view_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->view_ip;
			},
		];
		$this->templateColumns['deleted_date'] = [
			'attribute' => 'deleted_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->deleted_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'deleted_date'),
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->views, ['history-view/index', 'view'=>$model->primaryKey]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'html',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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
            $model = $model->where(['view_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	public function insertView($faq_id)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		
		$findView = self::find()
			->select(['view_id', 'publish', 'faq_id', 'user_id', 'views'])
			->where(['publish' => 1])
			->andWhere(['faq_id' => $faq_id]);
        if ($user_id != null) {
            $findView->andWhere(['user_id' => $user_id]);
        } else {
            $findView->andWhere(['is', 'user_id', null]);
        }
		$findView = $findView->one();
			
        if ($findView !== null) {
            $findView->updateAttributes(['views'=>$findView->views+1, 'view_ip'=>$_SERVER['REMOTE_ADDR']]);
        } else {
			$view = new FaqViews();
			$view->faq_id = $faq_id;
			$view->save();
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
            }

            $this->view_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
