<?php
/**
 * Faqs (faqs)
 * @var $this AdminController
 * @var $model Faqs
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-faqs
 *
 */

	$this->breadcrumbs=array(
		'Faqs'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
