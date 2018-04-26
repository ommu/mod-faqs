<?php
/**
 * Faq Views (faq-views)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\ViewsController
 * @var $model app\modules\faq\models\search\FaqViews
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 15:17 WIB
 * @contact (+62)857-4381-4273
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
		<?= $form->field($model, 'view_id') ?>

		<?= $form->field($model, 'publish') ?>

		<?= $form->field($model, 'faq_id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'views') ?>

		<?= $form->field($model, 'view_date') ?>

		<?= $form->field($model, 'view_ip') ?>

		<?= $form->field($model, 'deleted_date') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
