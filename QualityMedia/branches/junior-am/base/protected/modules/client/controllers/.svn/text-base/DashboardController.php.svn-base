<?php

/**
 * Dashboard controller.
 * This is the default Client module controller.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
class DashboardController extends ClientController
{

    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'view';

    /**
     * View dashboard action.
     */
    public function actionView()
    {
        // For now allow only own profile view
        $id = Yii::app()->getUser()->getId();

        $userModel = $this->loadModel($id, 'User');

        $businessId          = $userModel->billingInfo->id;
        $dashboardModel      = new ClientDashboard();
        $accountManagerModel = $userModel->accountManager;
        $profileModel        = $userModel->profile;
        $data                = array(
                'visitsSinceHire'               => $dashboardModel->getVisits($businessId),
                'customerInteractionsSinceHire' => $dashboardModel->getCustomerInteractions($businessId),
                'responsesWrittenSinceHire'     => $dashboardModel->getResponsesWritten($businessId),
                'visitsLastThirtyDays'          => $dashboardModel->getVisits($businessId, true),
                'customerInteractionsLastThirtyDays' => $dashboardModel->getCustomerInteractions($businessId, true),
                'accountManagerModel'           => $accountManagerModel,
                'profileModel'                  => $profileModel,
                'activities'                    => $dashboardModel->getLastActivities($businessId)
        );
        $this->render('view', $data);
    }

}