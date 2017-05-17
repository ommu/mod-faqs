<?php
/**
 * Faqs
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/FAQs
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
 * This is the model class for table "ommu_faqs".
 *
 * The followings are the available columns in table 'ommu_faqs':
 * @property string $faq_id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $user_id
 * @property string $modified_id
 * @property string $question
 * @property string $answer
 * @property integer $orders
 * @property integer $view
 * @property integer $likes
 * @property integer $comment
 * @property string $creation_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property OmmuFaqCategory $cat
 */
class Faqs extends CActiveRecord
{
	public $defaultColumns = array();
	public $questions;
	public $answers;
	
	// Variable Search
	public $cat_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Faqs the static model class
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
		return 'ommu_faqs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id,
				questions, answers', 'required'),
			array('publish, cat_id, orders, view, likes, comment', 'numerical', 'integerOnly'=>true),
			array('user_id, modified_id, question, answer', 'length', 'max'=>11),
			array('
				questions', 'length', 'max'=>128),
			array('user_id, modified_id, creation_date, modified_date,
				questions, answers', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('faq_id, publish, cat_id, user_id, modified_id, question, answer, orders, view, likes, comment, creation_date, modified_date,
				questions, answers, cat_search, user_search', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'FaqCategory', 'cat_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'question' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'question'),
			'answer' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'answer'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'faq_id' => Phrase::trans(11000,1),
			'publish' => Phrase::trans(11020,1),
			'cat_id' => Phrase::trans(11023,1),
			'user_id' => Phrase::trans(11033,1),
			'modified_id' => Phrase::trans(11039,1),
			'question' => Phrase::trans(11034,1),
			'answer' => Phrase::trans(11035,1),
			'orders' => Phrase::trans(11022,1),
			'view' => Phrase::trans(11036,1),
			'likes' => Phrase::trans(11037,1),
			'comment' => Phrase::trans(11040,1),
			'creation_date' => Phrase::trans(11024,1),
			'modified_date' => Phrase::trans(11025,1),
			'questions' => Phrase::trans(11034,1),
			'answers' => Phrase::trans(11035,1),
			'cat_search' => Phrase::trans(11023,1),
			'user_search' => Phrase::trans(11033,1),
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

		$criteria->compare('t.faq_id',$this->faq_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish') {
			$criteria->compare('t.publish',1);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish') {
			$criteria->compare('t.publish',0);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'trash') {
			$criteria->compare('t.publish',2);
		} else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		if(isset($_GET['category'])) {
			$criteria->compare('t.cat_id',$_GET['category']);
		} else {
			$criteria->compare('t.cat_id',$this->cat_id);
		}
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.modified_id',$this->modified_id);
		$criteria->compare('t.question',$this->question);
		$criteria->compare('t.answer',$this->answer);
		$criteria->compare('t.orders',$this->orders);
		$criteria->compare('t.view',$this->view);
		$criteria->compare('t.likes',$this->likes);
		$criteria->compare('t.comment',$this->comment);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		
		// Custom Search
		$criteria->with = array(
			'question' => array(
				'alias'=>'question',
				'select'=>'en'
			),
			'answer' => array(
				'alias'=>'answer',
				'select'=>'en'
			),
			'category.cat' => array(
				'alias'=>'cat',
				'select'=>'en'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
		);
		$criteria->compare('question.en',strtolower($this->questions), true);
		$criteria->compare('answer.en',strtolower($this->answers), true);
		$criteria->compare('cat.en',strtolower($this->cat_search), true);
		$criteria->compare('user.displayname',strtolower($this->user_search), true);

		if(!isset($_GET['Faqs_sort']))
			$criteria->order = 'faq_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
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
			//$this->defaultColumns[] = 'faq_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'modified_id';
			$this->defaultColumns[] = 'question';
			$this->defaultColumns[] = 'answer';
			$this->defaultColumns[] = 'orders';
			$this->defaultColumns[] = 'view';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'comment';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'modified_date';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->getTotalItemCount() - ($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) - $row'
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'questions',
				'value' => '"<div>".FaqCategory::getAdminCategory($data->cat_id,"select")."</div>".Phrase::trans($data->question, 2)."<br/><span>".Utility::shortText(Utility::hardDecode(Phrase::trans($data->answer, 2)),200)."</span>"',
				'htmlOptions' => array(
					'class' => 'bold',
				),
				'type' => 'raw',
			);
			if(!isset($_GET['category'])) {
				$this->defaultColumns[] = array(
					'name' => 'cat_search',
					'value' => 'CHtml::link(Phrase::trans($data->category->name, 2), Yii::app()->controller->createUrl(\'manage\', array(\'category\' => $data->cat_id)))',
					'type' => 'raw',
				);
			}
			/*
			$this->defaultColumns[] = array(
				'name' => 'user_search',
				'value' => '$data->user->displayname',
			);
			*/
			$this->defaultColumns[] = array(
				'name' => 'view',
				'value' => '$data->view',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'likes',
				'value' => '$data->likes',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'comment',
				'value' => '$data->comment',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->faq_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}

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
			} else {
				$this->modified_id = Yii::app()->user->id;
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		$location = Utility::getUrlTitle($currentAction);
				
		if(parent::beforeSave()) {
			if($this->isNewRecord || (!$this->isNewRecord && $this->question == 0)) {
				$question=new OmmuSystemPhrase;
				$question->location = $location.'_questions';
				$question->en_us = $this->questions;
				if($question->save())
					$this->question = $question->phrase_id;
				
			} else {
				$question = OmmuSystemPhrase::model()->findByPk($this->question);
				$question->en_us = $this->questions;
				$question->save();
			}
			
			if($this->isNewRecord || (!$this->isNewRecord && $this->answer == 0)) {				
				$answer=new OmmuSystemPhrase;
				$answer->location = $location.'_answers';
				$answer->en_us = $this->answers;
				if($answer->save())
					$this->answer = $answer->phrase_id;
				
			} else {				
				$answer = OmmuSystemPhrase::model()->findByPk($this->answer);
				$answer->en_us = $this->answers;
				$answer->save();
			}
		}
		return true;
	}
}