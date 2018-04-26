<?php
/**
 * HelpfulController
 * @var $this yii\web\View
 * @var $model app\modules\faq\models\FaqHelpful
 * version: 0.0.1
 *
 * HelpfulController implements the CRUD actions for FaqHelpful model.
 * Reference start
 * TOC :
 *  Index
 *  View
 *  Delete
 *
 *  findModel
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 9 January 2018, 08:35 WIB
 * @contact (+62)857-4381-4273
 *
 */
 
namespace app\modules\faq\controllers;

use Yii;
use app\modules\faq\models\FaqHelpful;
use app\modules\faq\models\search\FaqHelpful as HelpfulSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;

class HelpfulController extends Controller
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
     * Lists all FaqHelpful models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HelpfulSearch();
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

        $this->view->title = Yii::t('app', 'Helpfuls');
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns'     => $columns,
        ]);
    }

    /**
     * Displays a single FaqHelpful model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $this->view->title = Yii::t('app', 'View {modelClass}: {id}', ['modelClass' => 'FaqHelpful', 'id' => $model->id]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_view', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FaqHelpful model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        Yii::$app->session->setFlash('success', Yii::t('app', 'FaqHelpful success deleted.'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the FaqHelpful model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FaqHelpful the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(($model = FaqHelpful::findOne($id)) !== null) 
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
