<?php
/**
 * FaqStatistics
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-faqs
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
 * This is the model class for table "ommu_faq_statistics".
 *
 * The followings are the available columns in table 'ommu_faq_statistics':
 * @property string $date_key
 * @property string $faq_view
 * @property string $faq_insert
 * @property string $faq_update
 * @property string $faq_delete
 * @property string $faq_insert_category
 * @property string $faq_update_category
 * @property string $faq_delete_category
 * @property string $faq_likes
 * @property string $faq_unlikes
 * @property string $faq_comment
 * @property string $faq_comment_solved
 */
class FaqStatistics extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FaqStatistics the static model class
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
		return 'ommu_faq_statistics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_key', 'required'),
			array('faq_view, faq_insert, faq_update, faq_delete, faq_insert_category, faq_update_category, faq_delete_category, faq_likes, faq_unlikes, faq_comment, faq_comment_solved', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('date_key, faq_view, faq_insert, faq_update, faq_delete, faq_insert_category, faq_update_category, faq_delete_category, faq_likes, faq_unlikes, faq_comment, faq_comment_solved', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'date_key' => 'Date Key',
			'faq_view' => 'Faq View',
			'faq_insert' => 'Faq Insert',
			'faq_update' => 'Faq Update',
			'faq_delete' => 'Faq Delete',
			'faq_insert_category' => 'Faq Insert Category',
			'faq_update_category' => 'Faq Update Category',
			'faq_delete_category' => 'Faq Delete Category',
			'faq_likes' => 'Faq Likes',
			'faq_unlikes' => 'Faq Unlikes',
			'faq_comment' => 'Faq Comment',
			'faq_comment_solved' => 'Faq Comment Solved',
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

		$criteria->compare('t.date_key',$this->date_key,true);
		$criteria->compare('t.faq_view',$this->faq_view);
		$criteria->compare('t.faq_insert',$this->faq_insert);
		$criteria->compare('t.faq_update',$this->faq_update);
		$criteria->compare('t.faq_delete',$this->faq_delete);
		$criteria->compare('t.faq_insert_category',$this->faq_insert_category);
		$criteria->compare('t.faq_update_category',$this->faq_update_category);
		$criteria->compare('t.faq_delete_category',$this->faq_delete_category);
		$criteria->compare('t.faq_likes',$this->faq_likes);
		$criteria->compare('t.faq_unlikes',$this->faq_unlikes);
		$criteria->compare('t.faq_comment',$this->faq_comment);
		$criteria->compare('t.faq_comment_solved',$this->faq_comment_solved);

		if(!isset($_GET['FaqStatistics_sort']))
			$criteria->order = 'date_key DESC';

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
			//$this->defaultColumns[] = 'date_key';
			$this->defaultColumns[] = 'faq_view';
			$this->defaultColumns[] = 'faq_insert';
			$this->defaultColumns[] = 'faq_update';
			$this->defaultColumns[] = 'faq_delete';
			$this->defaultColumns[] = 'faq_insert_category';
			$this->defaultColumns[] = 'faq_update_category';
			$this->defaultColumns[] = 'faq_delete_category';
			$this->defaultColumns[] = 'faq_likes';
			$this->defaultColumns[] = 'faq_unlikes';
			$this->defaultColumns[] = 'faq_comment';
			$this->defaultColumns[] = 'faq_comment_solved';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = 'date_key';
			$this->defaultColumns[] = 'faq_view';
			$this->defaultColumns[] = 'faq_insert';
			$this->defaultColumns[] = 'faq_update';
			$this->defaultColumns[] = 'faq_delete';
			$this->defaultColumns[] = 'faq_insert_category';
			$this->defaultColumns[] = 'faq_update_category';
			$this->defaultColumns[] = 'faq_delete_category';
			$this->defaultColumns[] = 'faq_likes';
			$this->defaultColumns[] = 'faq_unlikes';
			$this->defaultColumns[] = 'faq_comment';
			$this->defaultColumns[] = 'faq_comment_solved';
		}
		parent::afterConstruct();
	}
}