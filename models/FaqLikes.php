<?php
/**
 * FaqLikes
 * 
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 8 January 2018, 16:52 WIB
 * @modified date 27 April 2018, 00:38 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
 *
 * This is the model class for table "ommu_faq_likes".
 *
 * The followings are the available columns in table "ommu_faq_likes":
 * @property integer $like_id
 * @property integer $publish
 * @property integer $faq_id
 * @property integer $user_id
 * @property string $likes_date
 * @property string $likes_ip
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property FaqLikeHistory[] $histories
 * @property Faqs $faq
 * @property Users $user
 *
 */

namespace ommu\faq\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class FaqLikes extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['likes_ip', 'updated_date'];

	// Variable Search
	public $category_search;
	public $faq_search;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_faq_likes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['faq_id', 'likes_ip'], 'required'],
			[['publish', 'faq_id', 'user_id'], 'integer'],
			[['likes_date', 'updated_date'], 'safe'],
			[['likes_ip'], 'string', 'max' => 20],
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
			'like_id' => Yii::t('app', 'Like'),
			'publish' => Yii::t('app', 'Publish'),
			'faq_id' => Yii::t('app', 'Faq'),
			'user_id' => Yii::t('app', 'User'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes IP'),
			'updated_date' => Yii::t('app', 'Updated Date'),
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
		return $this->hasMany(FaqLikeHistory::className(), ['like_id' => 'like_id']);
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
	 * @return \ommu\faq\models\query\FaqLikesQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\faq\models\query\FaqLikesQuery(get_called_class());
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
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
            $model = $model->where(['like_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	public function insertLike($faq_id, $action=false)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

        if ($action == false) {
            if ($user_id == null) {
                return 0;
            }

			$findLike = self::find()
				->select(['like_id', 'publish', 'faq_id', 'user_id'])	//1=like, 0=no like
				->where(['publish' => 1])
				->andWhere(['faq_id' => $faq_id])
				->andWhere(['user_id' => $user_id])
				->one();

            if ($findLike !== null) {
                return $findLike->like_id;
            } else {
                return 0;
            }

		} else {
			$findLike = self::find()
				->select(['like_id', 'publish', 'faq_id', 'user_id'])	//1=like, 0=no like
				->andWhere(['faq_id' => $faq_id]);
            if ($user_id != null) {
                $findLike->andWhere(['user_id' => $user_id]);
            } else {
                $findLike->andWhere(['is', 'user_id', null]);
            }
			$findLike = $findLike->one();
			
            if ($findLike !== null) {
				$publish = $findLike->publish == 1 ? 0 : 1;
				$findLike->updateAttributes(['publish' => $publish, 'view_ip' => $_SERVER['REMOTE_ADDR']]);

			} else {
				$view = new FaqViews();
				$view->publish = 1;
				$view->faq_id = $faq_id;
				$view->save();
			}
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

            $this->likes_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}

}
