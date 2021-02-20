<?php
/**
 * FaqCategory
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 4 January 2018, 16:24 WIB
 * @modified date 27 April 2018, 00:36 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
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
 * @property FaqCategory $parent
 *
 */

namespace ommu\faq\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\behaviors\SluggableBehavior;
use app\models\SourceMessage;
use app\models\Users;
use ommu\faq\models\view\FaqCategory as FaqCategoryView;

class FaqCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['orders', 'modified_date', 'modifiedDisplayname', 'updated_date', 'slug', 'cat_desc_i'];
	public $cat_name_i;
	public $cat_desc_i;

	// Variable Search
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $faq_search;
	public $faq_all_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_category';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'title.message',
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
			[['cat_name_i', 'cat_desc_i'], 'required'],
			[['publish', 'parent_id', 'cat_name', 'cat_desc', 'orders', 'creation_id', 'modified_id'], 'integer'],
			[['cat_name_i', 'cat_desc_i'], 'string'],
			[['orders', 'creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['cat_name_i'], 'string', 'max' => 64],
			[['cat_desc_i'], 'string', 'max' => 128],
			[['slug'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'publish' => Yii::t('app', 'Publish'),
			'parent_id' => Yii::t('app', 'Parent'),
			'cat_name' => Yii::t('app', 'Category'),
			'cat_desc' => Yii::t('app', 'Description'),
			'orders' => Yii::t('app', 'Orders'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'cat_name_i' => Yii::t('app', 'Category'),
			'cat_desc_i' => Yii::t('app', 'Description'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'faq_search' => Yii::t('app', 'Faqs'),
			'faq_all_search' => Yii::t('app', 'Faq All'),
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
	public function getTitle()
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
	public function getParent()
	{
		return $this->hasOne(FaqCategory::className(), ['cat_id' => 'parent_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(FaqCategoryView::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\query\FaqCategoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\faq\models\query\FaqCategoryQuery(get_called_class());
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
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['cat_name_i'] = [
			'attribute' => 'cat_name_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->title) ? $model->title->message : '-';
			},
		];
		$this->templateColumns['cat_desc_i'] = [
			'attribute' => 'cat_desc_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->description) ? $model->description->message : '-';
			},
		];
		$this->templateColumns['parent_id'] = [
			'attribute' => 'parent_id',
			//'filter' => self::getCategory(),
			'value' => function($model, $key, $index, $column) {
				return isset($model->parent) ? $model->parent->title->message : '-';
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
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
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['faq_search'] = [
			'attribute' => 'faq_search',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->faqs ? $model->view->faqs : 0, ['admin/index', 'category' => $model->primaryKey, 'publish' => 1]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'html',
		];
		$this->templateColumns['faq_all_search'] = [
			'attribute' => 'faq_all_search',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->faq_all ? $model->view->faq_all : 0, ['admin/index', 'category' => $model->primaryKey]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'html',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
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
            $model = $model->where(['cat_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($publish=null, $array=true)
	{
		$model = self::find()->alias('t')
			->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.cat_name=title.id');
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        }

		$model = $model->orderBy('title.message ASC')->all();

        if ($array == true) {
			$items = [];
            if ($model !== null) {
				foreach ($model as $val) {
					$items[$val->cat_id] = $val->title->message;
				}
				return $items;
			} else {
                return false;
            }
		} else {
            return $model;
        }
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		$this->cat_name_i = isset($this->title) ? $this->title->message : '';
		$this->cat_desc_i = isset($this->description) ? $this->description->message : '';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
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

        if (parent::beforeSave($insert)) {
            if ($insert || (!$insert && !$this->cat_name)) {
                $cat_name = new SourceMessage();
                $cat_name->location = $location.'_title';
                $cat_name->message = $this->cat_name_i;
                if ($cat_name->save()) {
                    $this->cat_name = $cat_name->id;
                }

            } else {
                $cat_name = SourceMessage::findOne($this->cat_name);
                $cat_name->message = $this->cat_name_i;
                $cat_name->save();
            }

            if ($insert || (!$insert && !$this->cat_desc)) {
                $cat_desc = new SourceMessage();
                $cat_desc->location = $location.'_description';
                $cat_desc->message = $this->cat_desc_i;
                if ($cat_desc->save()) {
                    $this->cat_desc = $cat_desc->id;
                }

            } else {
                $cat_desc = SourceMessage::findOne($this->cat_desc);
                $cat_desc->message = $this->cat_desc_i;
                $cat_desc->save();
            }
        }
        return true;
	}
}
