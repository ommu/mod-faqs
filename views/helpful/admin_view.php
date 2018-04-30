<?php
/**
 * Faq Helpfuls (faq-helpful)
 * @var $this yii\web\View
 * @var $this app\modules\faq\controllers\HelpfulController
 * @var $model app\modules\faq\models\FaqHelpful
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 9 January 2018, 08:35 WIB
 * @modified date 30 April 2018, 09:07 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faq Helpfuls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'category_search',
			'value' => isset($model->faq->category) ? $model->faq->category->title->message : '-',
		],
		[
			'attribute' => 'faq_search',
			'value' => isset($model->faq->questionRltn) ? $model->faq->questionRltn->message : '-',
		],
		[
			'attribute' => 'user_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'helpful',
			'value' => $model->helpful == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'message',
			'value' => $model->message ? $model->message : '-',
			'format' => 'html',
		],
		[
			'attribute' => 'helpful_date',
			'value' => !in_array($model->helpful_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->helpful_date, 'datetime') : '-',
		],
		'helpful_ip',
		[
			'attribute' => 'modified_date',
			'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
	],
]) ?>