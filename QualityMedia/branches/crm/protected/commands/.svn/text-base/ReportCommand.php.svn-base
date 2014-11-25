<?php
/**
 * Report command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ReportCommand extends CConsoleCommand
{
    /**
     * Initializes the command object.
     */
    public function init()
    {
        Yii::app()->getComponent('recurly');
    }

    /**
     * This method is invoked right after an action finishes execution.
     * @param string $action The action name
     * @param array $params The parameters to be passed to the action method.
     * @param integer $exitCode The application exit code returned by the action method.
     * @return integer application exit code
     */
    protected function afterAction($action, $params, $exitCode=0)
    {
        echo "\n\nDone\n";

        return parent::afterAction($action, $params, $exitCode);
    }

    /**
     * Generate failed invoices CSV report.
     */
    public function actionFailedInvoices()
    {
        // Get failed invoices from recurly
        $failedInvoices = Recurly_InvoiceList::getFailed();
        $now = new DateTime;    // Used to generate past due days

        $results = array();
        foreach($failedInvoices as $failedInvoice) {
            // Get account details
            $account = $failedInvoice->account->get();

            // Optimization. Protect before parsing the same account multiple times
            // One user may have multiple failed invoices
            if(isset($results[$account->account_code])) {
                continue;
            }

            // Get all invoices for given account
            $allInvoices = $account->invoices->get();

            // Get user to fetch related data (ie. salesman or account manager)
            $user = User::model()->findByAccountCode($account->account_code);

            $results[$account->account_code] = array(
                'companyName'       => $user->billingInfo->companyName,
                'name'              => $user->billingInfo->firstName . ' ' . $user->billingInfo->lastName,
                'debt'              => 0,
                'monthlyBill'       => 0,
                'pastDueDays'       => 0,
                'email'             => $user->email,
                'phoneNumber'       => $account->billing_info == null ? '' : $account->billing_info->get()->phone,
                'invoiceNumber'     => array(),
                'salesman'          => $user->salesman == null ? 'None' : $user->salesman->firstName . ' ' . $user->salesman->lastName,
                'accountManager'    => $user->accountManager == null ? 'None' : $user->accountManager->firstName . ' ' . $user->accountManager->lastName,
            );

            foreach($allInvoices as $invoice) {
                if($invoice->state == 'failed') {
                    $pastDueDays = $invoice->created_at->diff($now, true)->format('%a');

                    $results[$account->account_code]['debt']            += $invoice->total_in_cents;
                    $results[$account->account_code]['invoiceNumber'][] = $invoice->invoice_number;
                    $results[$account->account_code]['pastDueDays']     = max($results[$account->account_code]['pastDueDays'], $pastDueDays);
                }
            }

            foreach($user->subscriptions as $subscription) {
                if($subscription->state == 'active' || $subscription->state == 'expired') {
                    $results[$account->account_code]['monthlyBill'] += $subscription->unitAmount;
                    $results[$account->account_code]['monthlyBill'] += $subscription->addonsTotalAmount;
                }
            }

            $results[$account->account_code]['debt'] /= 100;
            $results[$account->account_code]['monthlyBill'] /= 100;
            $results[$account->account_code]['invoiceNumber'] = implode(', ', $results[$account->account_code]['invoiceNumber']);
        }

        $filename = Yii::getPathOfAlias('application').'/../failed-invoices.csv';

        $this->exportToCSV($filename, $results);
    }

    /**
     * Export to CSV.
     * @param string $path Path to file.
     * @param array $data Data to be saved in CSV
     */
    protected function exportToCSV($path, $data)
    {
        $fp = fopen($path, 'w');

        fputcsv($fp, array(
            'Company Name', 'Client Name', 'Debt', 'Monthly Bill', 'Past Due Days',
            'email', 'Phone Number', 'Invoice Number', 'Salesman', 'Account Manager',
        ));

        foreach($data as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }
}