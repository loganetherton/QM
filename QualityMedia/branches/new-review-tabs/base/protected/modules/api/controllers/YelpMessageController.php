<?php
/**
 * Yelp private messages controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpMessageController extends ApiController
{
    public function actionIndex()
    {
        $content = file_get_contents('/Users/zelu/Apache/odesk/glm/qualitymedia.com/dev1/scraper/phantomjs/messages_json.txt');
        $_POST = json_decode($content, true);

        $model = new YelpMessageReceiver;
        $model->setAttributes($_POST);

        if($model->save()) {
            $this->renderSuccess();
        }

        $this->renderError('Messages have not been saved');
    }

    /**
     * Create action.
     */
    public function actionCreate()
    {
        $model = new YelpMessageReceiver;
        $model->setAttributes($_POST);

        if($model->save()) {
            $this->renderSuccess();
        }

        $this->renderError('Messages have not been saved');
    }
}