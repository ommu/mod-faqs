<?php
/**
 * Faq Views (faq-views)
 * @var $this app\components\View
 * @var $this ommu\faq\controllers\ViewsController
 * @var $model ommu\faq\models\search\FaqViews
 * @var $form yii\widgets\ActiveForm
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 5 January 2018, 15:17 WIB
 * @modified date 29 April 2018, 19:23 WIB
 * @modified by Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?php echo $form->field($model, 'publish')
			->checkbox();?>

		<?php echo $form->field($model, 'faq_search');?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'views');?>

		<?php echo $form->field($model, 'view_date')
			->input('date');?>

		<?php echo $form->field($model, 'view_ip');?>

		<?php echo $form->field($model, 'deleted_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
