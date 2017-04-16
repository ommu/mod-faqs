<?php
/**
 * Faqs (faqs)
 * @var $this SiteController
 * @var $model Faqs
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/FAQs
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Faqs'=>array('manage'),
		$model->faq_id,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'faq_id',
		'publish',
		'cat_id',
		'user_id',
		'question',
		'answer',
		'orders',
		'view',
		'likes',
		'creation_date',
		'modified_date',
	),
)); ?>
