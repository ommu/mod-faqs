<?php
/**
 * Faqs
 * version: 0.0.1
 *
 * This is the model class for table "ommu_faqs".
 *
 * The followings are the available columns in table "ommu_faqs":
 * @property string $faq_id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $question
 * @property string $answer
 * @property integer $orders
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property FaqHelpful[] $helpfuls
 * @property FaqLikes[] $likes
 * @property FaqViews[] $views
 * @property FaqCategory $category

 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 16:00 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use app\coremodules\user\models\Users;
use app\libraries\grid\GridView;
use app\components\Utility;
use app\models\SourceMessage;

class Faqs extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['modified_date','modified_search','updated_date','slug'];

	// Variable Search
	public $cat_name_i;
    public $question_i;
    public $answer_i;
	public $creation_search;
	public $modified_search;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'ommu_faqs';
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
                'class'     => SluggableBehavior::className(),
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
         [['publish', 'cat_id', 'orders', 'question', 'answer', 'creation_id', 'modified_id'], 'integer'],
            [['cat_id', 'question_i', 'answer_i', 'orders', 'creation_id', 'modified_id', 'slug'], 'required'],
            [['creation_date', 'modified_date', 'updated_date'], 'safe'],
            [['slug'], 'string', 'max' => 128],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
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

     public function getName()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'cat_name']);
    }

    public function getQuestions()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'question']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'answer']);
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
			'category_search' => Yii::t('app', 'Category'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
            'answer_i' => Yii::t('app', 'Answer'),
            'question_i' => Yii::t('app', 'Question'),
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
         if(!isset($_GET['category'])) {
            $this->templateColumns['cat_id'] = [
                'attribute' => 'cat_id',
                'filter' => FaqCategory::getCategory(1),
                'value' => function($model, $key, $index, $column) {
                    return $model->cat_id ? $model->category->name->message: '-';
                },
            ];
        }
        $this->templateColumns['question_i'] = [
            'attribute' => 'question_i',
            'value' => function($model, $key, $index, $column) {
                return $model->question ? $model->questions->message : '-';
            },
        ];
        $this->templateColumns['answer_i'] = [
            'attribute' => 'answer_i',
            'value' => function($model, $key, $index, $column) {
                return $model->answer ? $model->answers->message : '-';
            },
        ];
        $this->templateColumns['orders'] = 'orders';
        $this->templateColumns['creation_date'] = [
            'attribute' => 'creation_date',
            'filter'    => \yii\jui\DatePicker::widget([
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
            'format'    => 'html',
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
            'filter'    => \yii\jui\DatePicker::widget([
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
            'format'    => 'html',
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
            'filter'    => \yii\jui\DatePicker::widget([
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
            'format'    => 'html',
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
                'format'    => 'raw',
            ];
        }
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
    public function beforeSave($insert) 
    {
        $module = strtolower(Yii::$app->controller->module->id);
        $controller = strtolower(Yii::$app->controller->id);
        $action = strtolower(Yii::$app->controller->action->id);
        $location = Utility::getUrlTitle($module.' '.$controller);

        if(parent::beforeSave($insert)) {
            if($this->isNewRecord || (!$this->isNewRecord && !$this->question)) {
                $question = new SourceMessage();
                //print_r($question);exit;
                $question->location = $location.'_question';
                $question->message = $this->question_i;
                if($question->save())
                    $this->question = $question->id;
                
            } else {
                $question = SourceMessage::findOne($this->question);
                $question->message = $this->question_i;
                $question->save();
            }

            if($this->isNewRecord || (!$this->isNewRecord && !$this->answer)) {
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
