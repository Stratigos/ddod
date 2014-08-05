<?php
namespace backend\controllers;

use Yii;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\models\Page;

/**
 * CRUD operations for Pages
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    /**
     * render inventory as list of Pages
     */
    public function actionIndex()
    {
        // TODO - move instance to controller property
        $pagesDataProvider = new ActiveDataProvider([
            'query'      => Page::find()->where(['deleted_at' => 0])->orderBy(['title' => SORT_ASC]),
            'pagination' => ['pageSize' => 10]
        ]);

        return $this->render(
            'index',
            [
                'createPageUrl'     => Yii::$app->urlManager->createUrl('page/create'),
                'pagesDataProvider' => $pagesDataProvider
            ]
        );
    }

    /**
     * create a new Page record
     */
    public function actionCreate()
    {
        $page   = new Page();
        $errors = [];

        if(Yii::$app->request->isPost) {
            $page->load(Yii::$app->request->post());
            if($page->save()) {
                return $this->redirect(['index']);
            } else {
                $errors = $page->getErrors();
            }
        }

        return $this->render(
            'create',
            [
                'page'   => $page,
                'errors' => $errors
            ]
        );
    }

    /**
     * edit an existing Page record
     */
    public function actionUpdate($id)
    {
        $page   = Page::find()->where('id = :_id', [':_id' => $id])->one();
        $errors = [];

        if($page === NULL) {
            throw new HttpException(404, "Page {$id} Not Found");
        }

        if(Yii::$app->request->isPost) {
            $page->load(Yii::$app->request->post());
            if($page->save()) {
                return $this->redirect(['index']);
            } else {
                $errors = $page->getErrors();
            }
        }

        return $this->render(
            'update',
            [
                'page'   => $page,
                'errors' => $errors
            ]
        );
    }

    /**
     * soft delete a Page record
     */
    public function actionDelete($id)
    {   
        //$errors = [];
        if($page = Page::find()->where('id = :_id', [':_id' => $id])->one()) {
            $page->deleted_at = time();
            if($page->save()) {
                return $this->redirect(['index']);
            } else {
                $errors[] = "Error deleting Page: {$id}";
            }
        }
        //  else {
        //     $errors[] = "Unable to locate Page: {$id}";
        // }

        // display errors
    }
}