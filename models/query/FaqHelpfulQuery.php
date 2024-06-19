<?php
/**
 * FaqHelpfulQuery
 *
 * This is the ActiveQuery class for [[\ommu\faq\models\FaqHelpful]].
 * @see \ommu\faq\models\FaqHelpful
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 27 April 2018, 00:38 WIB
 * @link https://github.com/ommu/mod-faqs
 *
 */

namespace ommu\faq\models\query;

class FaqHelpfulQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqHelpful[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\faq\models\FaqHelpful|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
