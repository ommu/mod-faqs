<?php
/**
 * Faq Categories (faq-category)
 * @var $this CategoryController
 * @var $model FaqCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-faqs
 *
 */

	$this->breadcrumbs=array(
		'Faq Categories'=>array('manage'),
		$model->name=>array('view','id'=>$model->cat_id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
