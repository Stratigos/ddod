<?php
/*******************************************
* Blogger (Post author) indices/verticals
********************************************/
namespace frontend\controllers;

use common\models\Blogger;
use frontend\dataproviders\BloggersDataProvider;
use frontend\dataproviders\BloggerPostsDataProvider;
use yii\web\HttpException;

class BloggerController extends FrontendController
{

    /**
     * Produce list of Bloggers' names, images, and URLs
     * @return VOID
     */
    public function actionIndex()
    {
        $blogDP = new BloggersDataProvider();
        return $this->render(
            'index',
            [
                'bloggers' => $blogDP->getModels()
            ]
        );
    }

    /**
     * Lists Blogger metadata, and all Posts authored by Blogger
     * @return VOID
     */
    public function actionBlogger($shortname)
    {
        if(!($blogger = Blogger::find()->published()->andWhere(['shortname' => $shortname])->one())) {
            throw new HttpException(404, 'Blogger not found.');
        }
        $bloggerPostsDP = new BloggerPostsDataProvider(['blogger_id' => $blogger->id]);

        return $this->render(
            'blogger',
            [
                'blogger' => $blogger,
                'postsDP' => $bloggerPostsDP
            ]
        );
    }
}
