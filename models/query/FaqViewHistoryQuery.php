<?php
/**
 * FaqViewHistoryQuery
 *
 * This is the ActiveQuery class for [[\app\modules\faq\models\__\FaqViewHistory]].
 * @see \app\modules\faq\models\__\FaqViewHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 27 April 2018, 06:57 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\faq\models\query;

class FaqViewHistoryQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\__\FaqViewHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\faq\models\__\FaqViewHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
