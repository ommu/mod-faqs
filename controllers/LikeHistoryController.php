<?php
/**
 * LikeHistoryController
 * @var $this yii\web\View
 * @var $model app\modules\faq\models\LikeHistory
 * version: 0.0.1
 *
 * LikeHistoryController implements the CRUD actions for LikeHistory model.
 * Reference start
 * TOC :
 *  Index
 *  Delete
 *  RunAction
 *
 *  findModel
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 9 January 2018, 08:22 WIB
 * @contact (+62)857-4381-4273
 *
 */
 
namespace app\modules\faq\controllers;

use Yii;
use app\modules\faq\models\LikeHistory;
use app\modules\faq\models\search\LikeHistory as LikeHistorySearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;

class LikeHistoryController extends Controller
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
     * Lists all LikeHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LikeHistorySearch();
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

        $this->view->title = Yii::t('app', 'Like Histories');
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns'     => $columns,
        ]);
    }

    /**
     * Creates a new LikeHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->publish = 2;

        if($model->save(false, ['publish'])) {
            //return $this->redirect(['view', 'id' => $model->id]);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Like History success deleted.'));
            return $this->redirect(['index']);
        }
    }
  
    /**
     * Finds the LikeHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return LikeHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(($model = LikeHistory::findOne($id)) !== null) 
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
