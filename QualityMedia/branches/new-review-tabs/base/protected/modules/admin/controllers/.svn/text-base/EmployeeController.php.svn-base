<?php
/**
 * Employees controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */

class EmployeeController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new Salesman('search');
        $managerModel = new AccountManager('search');

        $this->render('index', array(
            'model'=>$model,
            'managerModel'=> $managerModel
        ));
    }
}