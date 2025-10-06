<?php

namespace backend\controllers;

use backend\models\AuthorForm;
use backend\models\AuthorSearch;
use common\models\Author;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorsController extends Controller
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['list'],
                            'allow' => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Author models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Author model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $author = new Author();
        $formModel = AuthorForm::buildFromModel($author);

        if ($formModel->load($this->request->post()) && $formModel->updateAndSave($author)) {
            return $this->redirect(['view', 'id' => $author->id]);
        }

        return $this->render('create', [
            'model' => $formModel,
        ]);
    }

    /**
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $author = $this->findModel($id);
        $formModel = AuthorForm::buildFromModel($author);

        if ($this->request->isPost && $formModel->load($this->request->post()) && $formModel->updateAndSave($author)) {
            return $this->redirect(['view', 'id' => $author->id]);
        }

        return $this->render('update', [
            'model' => $formModel,
            'author' => $author,
        ]);
    }

    /**
     * Deletes an existing Author model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList($q = null)
    {
        $authors = Author::find()
            ->orFilterWhere(['like', 'first_name', $q])
            ->orFilterWhere(['like', 'last_name', $q])
            ->orFilterWhere(['like', 'surname', $q])
            ->limit(20)
            ->all();

        return $this->asJson(ArrayHelper::getColumn($authors, fn(Author $author) => [
            'id' => $author->id,
            'text' => $author->getFullName()
        ]));
    }
}
