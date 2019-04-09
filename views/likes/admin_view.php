<?php
/**
 * Faq Likes (faq-likes)
 * @var $this yii\web\View
 * @var $this ommu\faq\controllers\LikesController
 * @var $model ommu\faq\models\FaqLikes
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 8 January 2018, 17:06 WIB
 * @modified date 29 April 2018, 19:23 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-faqs
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faq Likes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->like_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger btn-sm'], 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'like_id',
		[
			'attribute' => 'publish',
			'value' => $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
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
			'attribute' => 'likes_date',
			'value' => Yii::$app->formatter->asDatetime($model->likes_date, 'medium'),
		],
		'likes_ip',
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
	],
]) ?>