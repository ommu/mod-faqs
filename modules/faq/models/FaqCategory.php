<?php
/**
 * FaqCategory
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
 * This is the model class for table "ommu_faq_category".
 *
 * The followings are the available columns in table 'ommu_faq_category':
 * @property integer $cat_id
 * @property integer $publish
 * @property string $user_id
 * @property string $modified_id
 * @property integer $dependency
 * @property integer $orders
 * @property string $name
 * @property string $desc
 * @property string $creation_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property OmmuFaqs[] $ommuFaqs
 */
class FaqCategory extends CActiveRecord
{
	public $defaultColumns = array();
	public $category;
	public $description;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FaqCategory the static model class
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
		return 'ommu_faq_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('
				category, description', 'required'),
			array('publish, dependency, orders', 'numerical', 'integerOnly'=>true),
			array('user_id, modified_id, name, desc', 'length', 'max'=>11),
			array('
				category', 'length', 'max'=>64),
			array('
				description', 'length', 'max'=>256),
			array('user_id, modified_id, creation_date, modified_date,
				description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cat_id, publish, user_id, modified_id, dependency, orders, name, desc, creation_date, modified_date,
				category, description', 'safe', 'on'=>'search'),
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
			'faq' => array(self::HAS_MANY, 'Faqs', 'cat_id'),
			'cat' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'name'),
			'desc' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'desc'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Phrase::trans(11023,1),
			'publish' => Phrase::trans(11020,1),
			'user_id' => Phrase::trans(11033,1),
			'modified_id' => Phrase::trans(11039,1),
			'dependency' => Phrase::trans(11021,1),
			'orders' => Phrase::trans(11022,1),
			'name' => Phrase::trans(11023,1),
			'desc' => Phrase::trans(11038,1),
			'creation_date' => Phrase::trans(11024,1),
			'modified_date' => Phrase::trans(11025,1),
			'category' => Phrase::trans(11023,1),
			'description' => Phrase::trans(11038,1),
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

		$criteria->compare('t.cat_id',$this->cat_id);
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
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.modified_id',$this->modified_id);
		$criteria->compare('t.dependency',$this->dependency);
		$criteria->compare('t.orders',$this->orders);
		$criteria->compare('t.name',$this->name);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		
		// Custom Search
		$criteria->with = array(
			'cat' => array(
				'alias'=>'cat',
				'select'=>'en'
			),
			'desc' => array(
				'alias'=>'desc',
				'select'=>'en'
			),
		);
		$criteria->compare('cat.en',strtolower($this->category), true);
		$criteria->compare('desc.en',strtolower($this->description), true);

		if(!isset($_GET['FaqCategory_sort']))
			$criteria->order = 'cat_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>50,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) 
	{
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
			//$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'modified_id';
			$this->defaultColumns[] = 'dependency';
			$this->defaultColumns[] = 'orders';
			$this->defaultColumns[] = 'name';
			$this->defaultColumns[] = 'desc';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'modified_date';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() 
	{
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'category',
				'value' => '"<div>".FaqCategory::getAdminCategory($data->cat_id,"select")."</div>".Phrase::trans($data->name, 2)."<br/><span>".Utility::shortText(Utility::hardDecode(Phrase::trans($data->desc, 2)),200)."</span>"',
				'htmlOptions' => array(
					'class' => 'bold',
				),
				'type' => 'raw',
			);
			/*
			$this->defaultColumns[] = array(
				'name' => 'dependency',
				'value' => '$data->dependency != 0 ? Phrase::trans(FaqCategory::model()->findByPk($data->dependency)->name, 2) : "-"',
			);
			*/
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
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->cat_id)), $data->publish, 1)',
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
	 * Get category
	 * 'all', 'group' = type render
	 * '0' = unpublish
	 * '1' = publish
	 */
	public static function getCategory($type='group', $dependency=0, $publish=null)
	{
		if($type == 'group') {
			if($publish == null) {
				$model = self::model()->findAll(array(
					//'select' => 'publish, name',
					'condition' => 'dependency = :dependency',
					'params' => array(
						':dependency' => $dependency,
					),
					'limit' => 100,
					//'order' => 'cat_id ASC'
				));
			} else {
				$model = self::model()->findAll(array(
					//'select' => 'publish, name',
					'condition' => 'publish = :publish AND dependency = :dependency',
					'params' => array(
						':publish' => $publish,
						':dependency' => $dependency,
					),
					'limit' => 100,
					//'order' => 'cat_id ASC'
				));
			}
		} else {
			if($publish == null) {
				$model = self::model()->findAll();
			} else {
				$model = self::model()->findAll(array(
					//'select' => 'publish, name',
					'condition' => 'publish = :publish',
					'params' => array(
						':publish' => $publish,
					),
					//'order' => 'cat_id ASC'
				));
			}
		}

		$items = array();
		if($model != null) {
			foreach($model as $key => $val) {
				if($type == 'group') {
					if($dependency == 0)
						$items[$val->cat_id] = Phrase::trans($val->name, 2);
					else 
						$items[$val->cat_id] = '- '.Phrase::trans($val->name, 2);
					if(self::getCategory($type, $val->cat_id, $publish) != null) {
						$data = self::getCategory($type, $val->cat_id, $publish);
						$items = $items + $data;
					}
				} else {
					$items[$val->cat_id] = Phrase::trans($val->name, 2);
				}
			}
		} else
			return false;
		
		return $items;
	}
	
	/**
	 * Get category
	 * 'ul', 'select' = type render
	 * '0' = unpublish
	 * '1' = publish
	 */
	public static function getGroupCategory($type='ul', $dependency=0, $publish=null)
	{
		$criteria=new CDbCriteria;
		if($publish == null) {
			$criteria->condition = 'dependency = :dependency';
			$criteria->params = array(
				':dependency' => $dependency,
			);
		} else {
			$criteria->condition = 'publish = :publish AND dependency = :dependency';
			$criteria->params = array(
				':publish' => $publish,
				':dependency' => $dependency,
			);
		}
		//$criteria->select = '';
		//$criteria->limit = 100,		
		$model = self::model()->find($criteria);
		
		$data = '';		
		if($model != null) {
			$data .= $type == 'ul' ? '<ul>' : '';
			foreach($model as $key => $val) {
				if($type == 'ul') {
					$data .= '<li>';
					$data .= '<a href="'.$val->cat_id.'" title="'.Phrase::trans($val->name, 2).'">'.Phrase::trans($val->name, 2).'</a>';
					if(self::getGroupCategory($type, $val->cat_id, $publish) != null) {
						$data .= self::getGroupCategory($type, $val->cat_id, $publish);
					}
					$data .= '</li>';
				} else {
					$data .= '<option value="'.$val->cat_id.'">'.Phrase::trans($val->name, 2).'</option>';
					if(self::getGroupCategory($type, $val->cat_id, $publish) != null)
						$data .= self::getGroupCategory($type, $val->cat_id, $publish);
				}
			}
			$data .= $type == 'ul' ? '</ul>' : '';
		}
			
		return $data;
	}
	
	/**
	 * Get parent category
	 */
	public static function getArrayParentCategory($id)
	{
		$model = self::model()->findByPk($id, array(
			//'select' => 'publish, name',
		));
		
		$items = array();
		if($model != null) {
			$items[$model->cat_id] = Phrase::trans($model->name, 2);
			if($model->dependency != 0) {
				$data = FaqCategory::getArrayParentCategory($model->dependency);
				$items = $items + $data;
			}
		}
		
		return $items;
	}
	
	/**
	 * Get parent category
	 */
	public static function getReverseParentCategory($id)
	{
		$model = self::getArrayParentCategory($id);
		$model = array_reverse($model, true);
		
		return $model;
	}
	
	/**
	 * Get parent category
	 */
	public static function getAdminCategory($id, $type='all')
	{
		$model = self::getReverseParentCategory($id);
		if($type != 'all')
			array_pop($model);
		
		$data = '';
		foreach($model as $key => $val) {
			$data .= '<a href="'.Yii::app()->controller->createUrl('admin/manage', array('category'=>$key)).'" title="'.$val.'">'.$val.'</a> ';
			$data .= '/ ';
		}
		
		return $data;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate()
	{
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
		$currentModule = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
		$location = Utility::getUrlTitle($currentModule);
		
		if(parent::beforeSave()) {
			if($this->isNewRecord || (!$this->isNewRecord && $this->name == 0)) {
				$cat=new OmmuSystemPhrase;
				$cat->location = $location.'_title';
				$cat->en_us = $this->category;
				if($cat->save())
					$this->name = $cat->phrase_id;
				
			} else {
				$cat = OmmuSystemPhrase::model()->findByPk($this->name);
				$cat->en_us = $this->category;
				$cat->update();
			}
			
			if($this->isNewRecord || (!$this->isNewRecord && $this->desc == 0)) {
				$desc=new OmmuSystemPhrase;
				$desc->location = $location.'_description';
				$desc->en_us = $this->description;
				if($desc->save())
					$this->desc = $desc->phrase_id;
				
			} else {
				$desc = OmmuSystemPhrase::model()->findByPk($this->desc);
				$desc->en_us = $this->description;
				$desc->update();
			}
		}
		return true;
	}
}