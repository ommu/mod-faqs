<?php
/**
 * FaqLikesQuery
 *
 * This is the ActiveQuery class for [[\app\modules\faq\models\FaqLikes]].
 * @see \app\modules\faq\models\FaqLikes
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 27 April 2018, 00:38 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\faq\models\query;

class FaqLikesQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * @inheritdoc
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\FaqLikes[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\FaqLikes|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
