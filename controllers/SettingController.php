<?php
/**
 * SettingController
 * @var $this yii\web\View
 * @var $model app\modules\faq\models\FaqSetting
 * version: 0.0.1
 *
 * SettingController implements the CRUD actions for FaqSetting model.
 * Reference start
 * TOC :
 *  Index
 *  Update
 *
 *  findModel
 *
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 4 January 2018, 14:44 WIB
 * @contact (+62)857-4381-4273
 *
 */
 
namespace app\modules\faq\controllers;

use Yii;
use app\modules\faq\models\FaqSetting;
use yii\data\ActiveDataProvider;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;

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
        $model = FaqSetting::findOne(1);
        if($model === null)
            $model = new FaqSetting();

        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if($model->save()) {
                //return $this->redirect(['view', 'id' => $model->id]);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Faq Setting success updated.'));
                return $this->redirect(['update']);
            }
        }

        $this->view->title = Yii::t('app', 'Update {modelClass}: {id}', ['modelClass' => 'Faq Setting', 'id' => $model->id]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_update', [
            'model' => $model,
        ]);
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
