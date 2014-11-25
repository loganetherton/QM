<?php
/**
 * Failed invoices CSV report.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class FailedInvoiceController extends AdminController
{
    /**
     * @var string $defaultAction The name of the default action.
     */
    public $defaultAction = 'create';

    /**
     * Send CSV report.
     */
    public function actionCreate()
    {
        $filename = Yii::getPathOfAlias('application').'/../failed-invoices.csv';
        $data = file_get_contents($filename);

        Yii::app()->getRequest()->sendFile('failed-invoices.csv', $data, 'text/csv');
    }
}