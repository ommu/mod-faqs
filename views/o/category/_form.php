<?php
/**
 * Faq Categories (faq-category)
 * @var $this CategoryController
 * @var $model FaqCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-faqs
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'faq-category-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<div class="dialog-content">
	
	<fieldset>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>
		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'dependency', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php if(FaqCategory::getCategory('group') != null) {
					echo $form->dropDownList($model,'dependency', FaqCategory::getCategory('group'), array('class'=>'form-control'));
				} else {
					echo $form->dropDownList($model,'dependency', array(0=>Yii::t('phrase', 'No Parent'), 'class'=>'form-control'));
				}?>
				<?php echo $form->error($model,'dependency'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'category', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				if(!$model->getErrors())
					$model->category = Phrase::trans($model->name);
				echo $form->textArea($model,'category', array('maxlength'=>64,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'category'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'description', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				if(!$model->getErrors())
					$model->description = Phrase::trans($model->desc);
				echo $form->textArea($model,'description', array('maxlength'=>256,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model,'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model,'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model,'publish'); ?>
				<?php echo $form->error($model,'publish'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>
