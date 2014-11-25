<?php
/**
 * Invoice PDF controller.
 * This controller allows to download invoice PDF.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class InvoicePdfController extends AdminController
{
    /**
     * View PDF.
     * @param integer $id Invoice id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id, 'Invoice');

        Yii::app()->getRequest()->sendFile(sprintf('QualityMedia-Invoice-%d.pdf', $model->number), $model->getPdf(), 'application/pdf');
    }
}