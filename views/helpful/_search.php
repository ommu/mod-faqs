<?php
/**
 * Helpfuls (helpful)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\HelpfulController
 * @var $model app\modules\faq\models\search\FaqHelpful
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 9 January 2018, 08:35 WIB
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
		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'faq_id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'helpful') ?>

		<?= $form->field($model, 'message') ?>

		<?= $form->field($model, 'helpful_date') ?>

		<?= $form->field($model, 'helpful_ip') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
