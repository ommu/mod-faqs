<?php
/**
 * Faq Categories (faq-category)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\CategoryController
 * @var $model app\modules\faq\models\FaqCategory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 5 January 2018, 10:08 WIB
 * @modified date 27 April 2018, 12:54 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faq Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
];
?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>