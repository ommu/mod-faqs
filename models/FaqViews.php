<?php
/**
 * FaqViews
 * version: 0.0.1
 *
 * This is the model class for table "ommu_faq_views".
 *
 * The followings are the available columns in table "ommu_faq_views":
 * @property string $view_id
 * @property integer $publish
 * @property string $faq_id
 * @property string $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 * @property string $deleted_date
 *
 * The followings are the available model relations:
 * @property FaqViewHistory[] $histories
 * @property Faqs $faq

 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 15:05 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;
use app\libraries\grid\GridView;

class FaqViews extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['deleted_date'];

	// Variable Search
	public $faq_search;
	public $user_search;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'ommu_faq_views';
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
         [['publish', 'faq_id', 'user_id', 'views'], 'integer'],
            [['faq_id', 'user_id', 'view_ip'], 'required'],
            [['view_date', 'deleted_date'], 'safe'],
            [['view_ip'], 'string', 'max' => 20],
            [['faq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faqs::className(), 'targetAttribute' => ['faq_id' => 'faq_id']],
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
			'view_ip' => Yii::t('app', 'View Ip'),
			'deleted_date' => Yii::t('app', 'Deleted Date'),
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
        $this->templateColumns['views'] = 'views';
        $this->templateColumns['view_date'] = [
            'attribute' => 'view_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'view_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                if(!in_array($model->view_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00'])) {
                    return Yii::$app->formatter->format($model->view_date, 'date'/*datetime*/);
                }else {
                    return '-';
                }
            },
            'format'    => 'html',
        ];
        $this->templateColumns['view_ip'] = 'view_ip';
        $this->templateColumns['deleted_date'] = [
            'attribute' => 'deleted_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'deleted_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                if(!in_array($model->deleted_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00'])) {
                    return Yii::$app->formatter->format($model->deleted_date, 'date'/*datetime*/);
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
