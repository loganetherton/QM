<?php
/**
 * Yelp reviews api controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpReviewController extends ApiController
{
    public function actionIndex()
    {
        $content = file_get_contents('/Users/zelu/Apache/odesk/glm/qualitymedia.com/dev1/scraper/phantomjs/reviews_json.txt');
        $_POST = json_decode($content, true);

        $model = new YelpReviewReceiver;
        $model->setAttributes($_POST);

        if($model->save()) {
            $this->renderSuccess();
        }

        $this->renderError('Reviews have not been saved');
    }

    /**
     * Create action.
     */
    public function actionCreate()
    {
        $model = new YelpReviewReceiver;
        $model->setAttributes($_POST);

        if($model->save()) {
            $this->renderSuccess();
        }

        $this->renderError('Reviews have not been saved');
    }
}