<?php
/**
 * /app/runtime/giiant/358b0e44f1c1670b558e36588c267e47
 *
 * @package default
 */


// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace dmstr\oauth\modules\admin\controllers\base;

use dmstr\oauth\modules\admin\models\Client;
use dmstr\oauth\modules\admin\models\search\Client as ClientSearch;
use yii\web\Controller;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{


    /**
     *
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;


    /**
     * Lists all Client models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        Yii::$app->session->set('__crudReturnUrl', null);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
    }


    /**
     * Displays a single Client model.
     *
     *
     * @throws \yii\web\HttpException
     * @param string  $id
     * @return string
     */
    public function actionView($id)
    {
        Yii::$app->session->set('__crudReturnUrl', Url::previous());
        Url::remember();
        Tabs::rememberActiveState();
        return $this->render('view', ['model' => $this->findModel($id)]);
    }


    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Client();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            if (!Yii::$app->request->isPost) {
                $model->load(Yii::$app->request->get());
            }
        } catch (\Exception $e) {
            $model->addError('_exception', $e->errorInfo[2] ?? $e->getMessage());
        }
        return $this->render('create', ['model' => $model]);
    }


    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @throws \yii\web\HttpException
     * @param string  $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load($_POST) && $model->save()) {
            return $this->redirect(Url::previous());
        }
        return $this->render('update', ['model' => $model]);
    }


    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @throws \Throwable
     * @param string  $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        } catch (\Exception $e) {
            Yii::$app->getSession()->addFlash('error', $e->errorInfo[2] ?? $e->getMessage());
            return $this->redirect(Url::previous());
        }


        return $this->redirect(['index']);
    }


    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @throws NotFoundHttpException if the model cannot be found
     * @param string  $id
     * @return Client the loaded model
     */
    protected function findModel($id)
    {
        $model = Client::findOne($id);
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('oauth', 'The requested page does not exist.'));
    }
}
