<?php
/**
 * Faqs (faqs)
 * @var $this AdminController
 * @var $model Faqs
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
	'id'=>'faqs-form',
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
			<?php echo $form->labelEx($model,'cat_id', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php if(FaqCategory::getCategory('group') != null) {
					echo $form->dropDownList($model,'cat_id', FaqCategory::getCategory('group'), array('class'=>'form-control'));
				} else {
					echo $form->dropDownList($model,'cat_id', array('prompt'=>Yii::t('phrase', 'No Parent')), array('class'=>'form-control'));
				}?>
				<?php echo $form->error($model,'cat_id'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'questions', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php
				if(!$model->getErrors())
					$model->questions = Phrase::trans($model->question);
				echo $form->textArea($model,'questions', array('maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'questions'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'answers', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php
				if(!$model->getErrors())
					$model->answers = Phrase::trans($model->answer);
				echo $form->textArea($model,'answers', array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'answers'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model,'publish', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
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
