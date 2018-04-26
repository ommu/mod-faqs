<?php
/**
 * AdminController
 * @var $this yii\web\View
 * @var $model app\modules\faq\models\Faqs
 * version: 0.0.1
 *
 * AdminController implements the CRUD actions for Faqs model.
 * Reference start
 * TOC :
 *  Index
 *  Create
 *  Update
 *  View
 *  Delete
 *  RunAction
 *  Publish
 *
 *  findModel
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 5 January 2018, 16:52 WIB
 * @contact (+62)857-4381-4273
 *
 */
 
namespace app\modules\faq\controllers;

use Yii;
use app\modules\faq\models\Faqs;
use app\modules\faq\models\search\Faqs as FaqsSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;

class AdminController extends Controller
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
                    'publish' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Faqs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqsSearch();
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

        $this->view->title = Yii::t('app', 'Faqs');
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns'     => $columns,
        ]);
    }

    /**
     * Creates a new Faqs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Faqs();

        if(Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());
             //print_r($model);exit;
            //var_dump($model);exit;
            if($model->save()) {
                //return $this->redirect(['view', 'id' => $model->faq_id]);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Faqs success created.'));
                return $this->redirect(['index']);
            } 
        }

        $this->view->title = Yii::t('app', 'Create Faqs');
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Faqs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if($model->save()) {
                //return $this->redirect(['view', 'id' => $model->faq_id]);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Faqs success updated.'));
                return $this->redirect(['index']);
            }
        }

        $this->view->title = Yii::t('app', 'Update {modelClass}: {faq_id}', ['modelClass' => 'Faqs', 'faq_id' => $model->faq_id]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Faqs model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $this->view->title = Yii::t('app', 'View {modelClass}: {faq_id}', ['modelClass' => 'Faqs', 'faq_id' => $model->faq_id]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_view', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Faqs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->publish = 2;

        if($model->save(false, ['publish'])) {
            //return $this->redirect(['view', 'id' => $model->faq_id]);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Faqs success deleted.'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Publish/Unpublish an existing Faqs model.
     * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        $replace = $model->publish == 1 ? 0 : 1;
        $model->publish = $replace;

        if($model->save(false, ['publish'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Faqs success updated.'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Faqs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Faqs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(($model = Faqs::findOne($id)) !== null) 
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
