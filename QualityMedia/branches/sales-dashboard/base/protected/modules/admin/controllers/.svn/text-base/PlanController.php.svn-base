<?php
/**
 * Recurly Plan controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PlanController extends AdminController
{
    /**
     * @var object Salesman Salesman object.
     */
    protected $salesman;

    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new Plan('search');
        $model->unsetAttributes();
        $salesmen = new Salesman;

        if(isset($_GET['Plan'])) {
            $model->setAttributes($_GET['Plan']);
        }

        $this->salesman = new Salesman;
        if(isset($_GET['Salesman']['id']) && !empty($_GET['Salesman']['id'])) {
            $this->salesman = $this->loadModel((int) $_GET['Salesman']['id'], 'Salesman');
        }

        $this->render('index', array(
            'model'=>$model,
            'salesmen'=> $salesmen,
        ));
    }

    /**
     * Returns link to salesman custom registration form.
     * @param object $data Model instance
     * @return string Absolute URL to salesman custom registration form
     */
    protected function getCustomFormLink($data)
    {
        $params = array('plan'=>$data->planCode);
        if($this->salesman->id) {
            $params['salesman'] = $this->salesman->username;
        }
        return $this->createAbsoluteUrl('/registration/create', $params);
    }
}