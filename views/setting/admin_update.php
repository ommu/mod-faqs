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
use yii\helpers\Url;
use app\libraries\MenuContent;
use app\components\Utility;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faq Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<?php if(Yii::$app->session->hasFlash('success'))
		echo Utility::flashMessage(Yii::$app->session->getFlash('success'));
	else if(Yii::$app->session->hasFlash('error'))
		echo Utility::flashMessage(Yii::$app->session->getFlash('error'), 'danger');?>

	<div class="x_panel">
		<div class="x_content">
			<?php echo $this->render('_form', [
				'model' => $model,
			]); ?>
		</div>
	</div>
</div>