<?php
/**
 * Faqs
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 January 2018, 16:00 WIB
 * @modified date 27 April 2018, 00:36 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_faqs".
 *
 * The followings are the available columns in table "ommu_faqs":
 * @property integer $faq_id
 * @property integer $publish
 * @property integer $cat_id
 * @property integer $question
 * @property integer $answer
 * @property integer $orders
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property FaqHelpful[] $helpfuls
 * @property FaqLikes[] $likes
 * @property FaqViews[] $views
 * @property FaqCategory $category
 * @property SourceMessage $questionRltn
 * @property SourceMessage $answerRltn
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\faq\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\behaviors\SluggableBehavior;
use app\models\SourceMessage;
use ommu\users\models\Users;
use ommu\faq\models\view\Faqs as FaqsView;

class Faqs extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['answer_i','orders','modified_date','modified_search','updated_date','slug'];
	public $question_i;
	public $answer_i;

	// Variable Search
	public $category_search;
	public $creation_search;
	public $modified_search;
	public $helpful_search;
	public $view_search;
	public $like_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faqs';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'faq_id',
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
			[['cat_id', 'question_i', 'answer_i'], 'required'],
			[['publish', 'cat_id', 'question', 'answer', 'orders', 'creation_id', 'modified_id'], 'integer'],
			[['question_i', 'answer_i'], 'string'],
			[['orders', 'creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['question_i'], 'string', 'max' => 128],
			[['slug'], 'string', 'max' => 128],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'faq_id' => Yii::t('app', 'Faq'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'question' => Yii::t('app', 'Question'),
			'answer' => Yii::t('app', 'Answer'),
			'orders' => Yii::t('app', 'Orders'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'question_i' => Yii::t('app', 'Question'),
			'answer_i' => Yii::t('app', 'Answer'),
			'category_search' => Yii::t('app', 'Category'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
			'helpful_search' => Yii::t('app', 'helpfuls'),
			'view_search' => Yii::t('app', 'Views'),
			'like_search' => Yii::t('app', 'Likes'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHelpfuls()
	{
		return $this->hasMany(FaqHelpful::className(), ['faq_id' => 'faq_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLikes()
	{
		return $this->hasMany(FaqLikes::className(), ['faq_id' => 'faq_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews()
	{
		return $this->hasMany(FaqViews::className(), ['faq_id' => 'faq_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(FaqCategory::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getQuestionRltn()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'question']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAnswerRltn()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'answer']);
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
	public function getView()
	{
		return $this->hasOne(FaqsView::className(), ['faq_id' => 'faq_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\query\FaqsQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\faq\models\query\FaqsQuery(get_called_class());
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
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['cat_id'] = [
				'attribute' => 'cat_id',
				'filter' => FaqCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
				},
			];
		}
		$this->templateColumns['question_i'] = [
			'attribute' => 'question_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->questionRltn) ? $model->questionRltn->message : '-';
			},
		];
		$this->templateColumns['answer_i'] = [
			'attribute' => 'answer_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->answerRltn) ? $model->answerRltn->message : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['slug'] = [
			'attribute' => 'slug',
			'value' => function($model, $key, $index, $column) {
				return $model->slug;
			},
		];
		$this->templateColumns['orders'] = [
			'attribute' => 'orders',
			'value' => function($model, $key, $index, $column) {
				return $model->orders;
			},
			'filter' => false,
		];
		$this->templateColumns['helpful_search'] = [
			'attribute' => 'helpful_search',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->helpfuls ? $model->view->helpfuls : 0, ['helpful/index', 'faq'=>$model->primaryKey]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['view_search'] = [
			'attribute' => 'view_search',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->views ? $model->view->views : 0, ['views/index', 'faq'=>$model->primaryKey, 'publish' => 1]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['like_search'] = [
			'attribute' => 'like_search',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->likes ? $model->view->likes : 0, ['likes/index', 'faq'=>$model->primaryKey, 'publish' => 1]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
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
				->where(['faq_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getFaqs
	 */
	public static function getFaq($publish=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->leftJoin(sprintf('%s questionRltn', SourceMessage::tableName()), 't.question=questionRltn.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('questionRltn.message ASC')->all();

		if($array == true) {
			$items = [];
			if($model !== null) {
				foreach($model as $val) {
					$items[$val->faq_id] = $val->questionRltn->message;
				}
				return $items;
			} else
				return false;
		} else 
			return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind() 
	{
		$this->question_i = isset($this->questionRltn) ? $this->questionRltn->message : '';
		$this->answer_i = isset($this->answerRltn) ? $this->answerRltn->message : '';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
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

		$location = Inflector::slug($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->question)) {
				$question = new SourceMessage();
				$question->location = $location.'_question';
				$question->message = $this->question_i;
				if($question->save())
					$this->question = $question->id;
				
			} else {
				$question = SourceMessage::findOne($this->question);
				$question->message = $this->question_i;
				$question->save();
			}

			if($insert || (!$insert && !$this->answer)) {
				$answer = new SourceMessage();
				$answer->location = $location.'_answer';
				$answer->message = $this->answer_i;
				if($answer->save())
					$this->answer = $answer->id;
				
			} else {
				$answer = SourceMessage::findOne($this->answer);
				$answer->message = $this->answer_i;
				$answer->save();
			}
		}
		return true;
	}
}
