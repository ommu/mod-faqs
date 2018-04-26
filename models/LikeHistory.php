<?php
/**
 * LikeHistory
 * version: 0.0.1
 *
 * This is the model class for table "ommu_faq_like_history".
 *
 * The followings are the available columns in table "ommu_faq_like_history":
 * @property string $id
 * @property integer $publish
 * @property string $like_id
 * @property string $likes_date
 * @property string $likes_ip
 *
 * The followings are the available model relations:
 * @property FaqLikes $like

 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 9 January 2018, 08:19 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\faq\models;

use Yii;
use yii\helpers\Url;
use app\libraries\grid\GridView;

class LikeHistory extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['publish'];

	// Variable Search
	public $like_search;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'ommu_faq_like_history';
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
         [['publish', 'like_id', 'likes_ip'], 'required'],
            [['publish', 'like_id'], 'integer'],
            [['likes_date'], 'safe'],
            [['likes_ip'], 'string', 'max' => 20],
            [['like_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqLikes::className(), 'targetAttribute' => ['like_id' => 'like_id']],
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
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'like_id' => Yii::t('app', 'Like'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes Ip'),
			'like_search' => Yii::t('app', 'Like'),
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
        if(!Yii::$app->request->get('like')) {
            $this->templateColumns['like_search'] = [
                'attribute' => 'like_search',
                'value' => function($model, $key, $index, $column) {
                    return $model->like->like_id;
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
