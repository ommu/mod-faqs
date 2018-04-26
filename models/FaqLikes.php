<?php
/**
 * FaqLikes
 * version: 0.0.1
 *
 * This is the model class for table "ommu_faq_likes".
 *
 * The followings are the available columns in table "ommu_faq_likes":
 * @property string $like_id
 * @property integer $publish
 * @property string $faq_id
 * @property string $user_id
 * @property string $likes_date
 * @property string $likes_ip
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property FaqLikeHistory[] $histories
 * @property Faqs $faq

 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 8 January 2018, 16:52 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;
use app\libraries\grid\GridView;

class FaqLikes extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	// Variable Search
	public $faq_search;
	public $user_search;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'ommu_faq_likes';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('ecc4');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
         [['publish', 'faq_id', 'user_id'], 'integer'],
            [['faq_id', 'user_id', 'likes_ip'], 'required'],
            [['likes_date', 'updated_date'], 'safe'],
            [['likes_ip'], 'string', 'max' => 20],
            [['faq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faqs::className(), 'targetAttribute' => ['faq_id' => 'faq_id']],
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
			'likes_ip' => Yii::t('app', 'Likes Ip'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'faq_search' => Yii::t('app', 'Faq'),
			'user_search' => Yii::t('app', 'User'),
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
        if(!Yii::$app->request->get('faq')) {
            $this->templateColumns['faq_search'] = [
                'attribute' => 'faq_search',
                'value' => function($model, $key, $index, $column) {
                    return $model->faq->faq_id;
                },
            ];
        }
        if(!Yii::$app->request->get('user')) {
            $this->templateColumns['user_search'] = [
                'attribute' => 'user_search',
                'value' => function($model, $key, $index, $column) {
                    return isset($model->user->displayname) ? $model->user->displayname : '-';
                },
            ];
        }
        $this->templateColumns['likes_date'] = [
            'attribute' => 'likes_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'likes_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                if(!in_array($model->likes_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00'])) {
                    return Yii::$app->formatter->format($model->likes_date, 'date'/*datetime*/);
                }else {
                    return '-';
                }
            },
            'format'    => 'html',
        ];
        $this->templateColumns['likes_ip'] = 'likes_ip';
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
        }
        return true;
    }

}
