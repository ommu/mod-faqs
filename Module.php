<?php
namespace app\modules\faq;

/**
 * faq module definition class
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 4 January 2018, 11:49 WIB
 * @contact (+62)85743814273
 *
 */
class Module extends \app\components\Module
{
	public $layout = 'main';

	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'app\modules\faq\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
