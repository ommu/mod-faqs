<?php
/**
 * Faq Settings (faq-setting)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\SettingController
 * @var $model app\modules\faq\models\FaqSetting
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 4 January 2018, 14:44 WIB
 * @contact (+62)857-4381-4273
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\faq\models\FaqSetting;
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
if($model->isNewRecord || (!$model->isNewRecord && $model->license == ''))
    $model->license = FaqSetting::getLicense();
echo $form->field($model, 'license', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('license'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
<?php 
$permission = [
    1 => Yii::t('app', 'Yes, the public can view faq unless they are made private.'),
    0 => Yii::t('app', 'No, the public cannot view faq.'),
];

echo $form->field($model, 'permission', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12"><span class="small-px">'.Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.').'</span>{input}{error}</div>'])
	->radioList($permission, ['class'=>'desc pt-10', 'separator' => '<br />'])
    ->label($model->getAttributeLabel('permission'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_keyword', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_keyword'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_description', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_description'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>