<?php
/**
 * HistoryViewController
 * @var $this yii\web\View
 * @var $model app\modules\faq\models\FaqViewHistory
 * version: 0.0.1
 *
 * HistoryViewController implements the CRUD actions for FaqViewHistory model.
 * Reference start
 * TOC :
 *  Index
 *  Delete
 *
 *  findModel
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 8 January 2018, 15:19 WIB
 * @contact (+62)857-4381-4273
 *
 */
 
namespace app\modules\faq\controllers;

use Yii;
use app\modules\faq\models\FaqViewHistory;
use app\modules\faq\models\search\FaqViewHistory as FaqViewHistorySearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;

class HistoryViewController extends Controller
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
     * Lists all FaqViewHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqViewHistorySearch();
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

        $this->view->title = Yii::t('app', 'Faq View Histories');
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns'     => $columns,
        ]);
    }

    /**
     * Creates a new FaqViewHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
        /**
     * Deletes an existing FaqViewHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        Yii::$app->session->setFlash('success', Yii::t('app', 'Faq View History success deleted.'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the FaqViewHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FaqViewHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(($model = FaqViewHistory::findOne($id)) !== null) 
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
