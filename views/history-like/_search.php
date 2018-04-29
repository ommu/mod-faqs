<?php
/**
 * Faq Like Histories (faq-like-history)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\HistoryLikeController
 * @var $model app\modules\faq\models\search\FaqLikeHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 9 January 2018, 08:22 WIB
 * @modified date 29 April 2018, 20:31 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
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

		<?php echo $form->field($model, 'like_search');?>

		<?php echo $form->field($model, 'likes_date')
			->input('date');?>

		<?php echo $form->field($model, 'likes_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
