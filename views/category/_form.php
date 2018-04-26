<?php
/**
 * Faq Categories (faq-category)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\CategoryController
 * @var $model app\modules\faq\models\FaqCategory
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 10:08 WIB
 * @contact (+62)857-4381-4273
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\faq\models\FaqCategory;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'form-horizontal form-label-left',
        //'enctype' => 'multipart/form-data',
    ],
    'enableClientValidation' => true,
    'enableAjaxValidation'   => false,
    //'enableClientScript'     => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
$category = FaqCategory::getCategory(1);
echo $form->field($model, 'parent', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('parent'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
// if(!$model->getErrors())
if (isset($model->name->message))
	$model->cat_name = $model->name->message;
echo $form->field($model, 'cat_name', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('cat_name'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
// if(!$model->getErrors())
if (isset($model->description->message))
	$model->cat_desc_i = $model->description->message;
echo $form->field($model, 'cat_desc_i', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6,'maxlength' => true])
	->label($model->getAttributeLabel('cat_desc_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'orders', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('orders'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>