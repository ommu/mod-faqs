<?php
/**
 * FaqLikes
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-frequency-asked-question
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_faq_likes".
 *
 * The followings are the available columns in table 'ommu_faq_likes':
 * @property string $like_id
 * @property string $faq_id
 * @property string $user_id
 * @property string $likes_date
 * @property string $likes_ip
 *
 * The followings are the available model relations:
 * @property Faqs $faq
 */
class FaqLikes extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FaqLikes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_faq_likes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('faq_id, user_id, likes_ip', 'required'),
			array('faq_id, user_id', 'length', 'max'=>11),
			array('likes_ip', 'length', 'max'=>20),
			array('likes_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('like_id, faq_id, user_id, likes_date, likes_ip', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'faq' => array(self::BELONGS_TO, 'OmmuFaqs', 'faq_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'like_id' => Phrase::trans(11037,1),
			'faq_id' => Phrase::trans(11000,1),
			'user_id' => Phrase::trans(11033,1),
			'likes_date' => Phrase::trans(11044,1),
			'likes_ip' => Phrase::trans(11045,1),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.like_id',$this->like_id);
		$criteria->compare('t.faq_id',$this->faq_id);
		$criteria->compare('t.user_id',$this->user_id);
		if($this->likes_date != null && !in_array($this->likes_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.likes_date)',date('Y-m-d', strtotime($this->likes_date)));
		$criteria->compare('t.likes_ip',$this->likes_ip,true);

		if(!isset($_GET['FaqLikes_sort']))
			$criteria->order = 'like_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		}else {
			//$this->defaultColumns[] = 'like_id';
			$this->defaultColumns[] = 'faq_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'likes_date';
			$this->defaultColumns[] = 'likes_ip';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			//$this->defaultColumns[] = 'like_id';
			$this->defaultColumns[] = 'faq_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.components.system.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'creation_date', 
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->defaultColumns[] = 'likes_ip';
		}
		parent::afterConstruct();
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {		
			if($this->isNewRecord) {
				$this->user_id = Yii::app()->user->id;
				$this->likes_ip = $_SERVER['REMOTE_ADDR'];
			}
		}
		return true;
	}
}