<?php
/**
 * Controller for handling various client actions
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class ClientsController extends AmController
{
    /**
     * Default action for this controller, shows a list of clients along with there notes
     *
     * @access public
     * @return void
     */
    public function actionIndex()
    {
        $client = new Client;
        $client->accountManager(Yii::app()->getUser()->id);

        if (isset($_GET['Client'])) {
            $client->companyName = $_GET['Client']['companyName'];
        }

        $this->render('index', array(
            'data' => $client->search(),
            'model' => $client,
        ));
    }
}