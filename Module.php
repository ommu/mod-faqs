<?php
namespace ommu\faq;

/**
 * faq module definition class
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-faqs
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
	public $controllerNamespace = 'ommu\faq\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
