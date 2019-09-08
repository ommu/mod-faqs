<?php
/**
 * FaqLikesQuery
 *
 * This is the ActiveQuery class for [[\ommu\faq\models\FaqLikes]].
 * @see \ommu\faq\models\FaqLikes
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 April 2018, 00:38 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\faq\models\query;

class FaqLikesQuery extends \yii\db\ActiveQuery
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
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted() 
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqLikes[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqLikes|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
