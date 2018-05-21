<?php
/**
 * SettingController
 * @var $this yii\web\View
 * @var $model ommu\faq\models\FaqSetting
 *
 * SettingController implements the CRUD actions for FaqSetting model.
 * Reference start
 * TOC :
 *  Index
 *  Update
 *	Delete
 *
 *	findModel
 *
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @contact (+62)857-4381-4273
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 4 January 2018, 14:44 WIB
 * @modified date 27 April 2018, 10:39 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
 *
 */
 
namespace ommu\faq\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\faq\models\FaqSetting;
use ommu\faq\models\search\FaqCategory as FaqCategorySearch;

class SettingController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all FaqSetting models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->redirect(['update']);
	}

	/**
	 * Updates an existing FaqSetting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		$this->layout = 'admin_default';

		$searchModel = new FaqCategorySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		$model = FaqSetting::findOne(1);
		if($model === null)
			$model = new FaqSetting();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Faq setting success updated.'));
				return $this->redirect(['update']);
				//return $this->redirect(['view', 'id' => $model->id]);
			}
		}

		$this->view->title = Yii::t('app', 'FAQ Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing FaqSetting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Faq setting success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Finds the FaqSetting model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return FaqSetting the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = FaqSetting::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
