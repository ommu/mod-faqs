<?php
/**
 * Faq Likes (faq-likes)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\LikesController
 * @var $model app\modules\faq\models\FaqLikes
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 8 January 2018, 17:06 WIB
 * @contact (+62)857-4381-4273
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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

<?php echo $form->field($model, 'faq_id', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('faq_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'user_id', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('user_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'likes_ip', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('likes_ip'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

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