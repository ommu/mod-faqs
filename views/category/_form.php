<?php
/**
 * Faq Categories (faq-category)
 * @var $this app\components\View
 * @var $this ommu\faq\controllers\CategoryController
 * @var $model ommu\faq\models\FaqCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 5 January 2018, 10:08 WIB
 * @modified date 27 April 2018, 12:54 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\faq\models\FaqCategory;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = FaqCategory::getCategory(1);
echo $form->field($model, 'parent_id')
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('parent_id')); ?>

<?php echo $form->field($model, 'cat_name_i')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('cat_name_i')); ?>

<?php echo $form->field($model, 'cat_desc_i')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('cat_desc_i')); ?>

<?php echo $form->field($model, 'orders')
	->textInput(['type' => 'number', 'min' => '0'])
	->label($model->getAttributeLabel('orders'));?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>