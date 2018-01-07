<?php
/**
 * Faq Setting (faq-setting)
 * @var $this SettingController
 * @var $model FaqSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-faqs
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'faq-setting-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<fieldset>

	<div class="form-group row">
		<label class="col-form-label col-lg-4 col-md-3 col-sm-12">
			<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
			<span><?php echo Yii::t('phrase', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.');?></span>
		</label>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo $form->textField($model,'license',array('maxlength'=>32,'class'=>'form-control','disabled'=>'disabled')); ?>
			<?php echo $form->error($model,'license'); ?>
			<span class="small-px"><?php echo Yii::t('phrase', 'Format: XXXX-XXXX-XXXX-XXXX');?></span>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'permission', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<span class="small-px"><?php echo Yii::t('phrase', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.');?></span>
			<?php echo $form->radioButtonList($model, 'permission', array(
				1 => Yii::t('phrase', 'Yes, the public can view faqs unless they are made private.'),
				0 => Yii::t('phrase', 'No, the public cannot view faqs.'),
			), array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'permission'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'meta_description', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo $form->textArea($model,'meta_description',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'meta_description'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'meta_keyword', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo $form->textArea($model,'meta_keyword',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'meta_keyword'); ?>
		</div>
	</div>

	<div class="form-group row submit">
		<label class="col-form-label col-lg-4 col-md-3 col-sm-12">&nbsp;</label>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>
