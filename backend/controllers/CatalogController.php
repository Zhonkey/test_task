<?php

namespace backend\controllers;

use backend\models\BookSearch;
use backend\models\SubscribeForm;
use common\components\report\topYear\TopYearReport;
use common\models\Book;
use common\models\Subscriber;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BooksController implements the CRUD actions for Book model.
 */
class CatalogController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'subscribe' => ['POST'],
                    ],
                ]
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionReport($year = null)
    {
        $report = new TopYearReport();
        if($year){
            $reportData = $report->request($year);
        } else {
            $reportData = $report->totalYearsTop();
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $reportData,
        ]);

        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'year' => $year,
        ]);
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $search = new BookSearch();

        $dataProvider = $search->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $search
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $subscriber = Subscriber::findOne(Yii::$app->session->get('subscriberId'));

        return $this->render('view', [
            'model' => $this->findModel($id),
            'subscriber' => $subscriber
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionSubscribe()
    {
        $form = new SubscribeForm();

        if ($form->load($this->request->post())) {
            if($form->subscribe()) {
                Yii::$app->session->set('subscriberId', $form->getSubscriber()->id);
            }
        }

        return $this->goBack();
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
