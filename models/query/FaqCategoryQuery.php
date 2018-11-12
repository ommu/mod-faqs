<?php
/**
 * FaqCategoryQuery
 *
 * This is the ActiveQuery class for [[\ommu\faq\models\FaqCategory]].
 * @see \ommu\faq\models\FaqCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 April 2018, 00:36 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\faq\models\query;

class FaqCategoryQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqCategory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqCategory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
