<?php
/**
 * Salesman custom subscription form.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SalesmanPlanController extends AdminController
{
    /**
     * @var object Salesman Salesman object.
     */
    protected $salesman;

    /**
     * Display available plans.
     * @param integer $id Salesman id
     */
    public function actionIndex($id)
    {
        $this->salesman = $this->loadModel($id, 'Salesman');

        $model = new Plan('search');
        $model->unsetAttributes();

        if(isset($_GET['Plan'])) {
            $model->setAttributes($_GET['Plan']);
        }

        $this->render('index', array(
            'model'=>$model,
            'salesman'=>$this->salesman,
        ));
    }

    /**
     * Returns link to salesman custom registration form.
     * @param object $data Model instance
     * @return string Absolute URL to salesman custom registration form
     */
    protected function getCustomFormLink($data)
    {
        return $this->createAbsoluteUrl('/registration/create', array('plan'=>$data->planCode, 'salesman'=>$this->salesman->username));
    }
}