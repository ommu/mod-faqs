<?php
/**
 * FaqViewHistoryQuery
 *
 * This is the ActiveQuery class for [[\ommu\faq\models\FaqViewHistory]].
 * @see \ommu\faq\models\FaqViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 27 April 2018, 06:57 WIB
 * @link https://github.com/ommu/mod-faqs
 *
 */

namespace ommu\faq\models\query;

class FaqViewHistoryQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqViewHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqViewHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
