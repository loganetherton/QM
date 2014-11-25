<?php
/**
 * Create Products form.
 * This class handles pricing products choice page
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class ProductsForm extends CFormModel
{
    /**
     * Returns enabled plans data
     * @return [type] [description]
     */
    public function getPlans()
    {
        $plans = array();

        $plansCodes = Yii::app()->params['pricingPlans'];

        foreach($plansCodes as $planCode) {
            $plans[$planCode] = Plan::model()->findByPlanCode($planCode);
        }

        return $plans;
    }

    public function plansDropDownList() {
        $plans = array();

        $plansCodes = Yii::app()->params['pricingPlans'];

        foreach($plansCodes as $planCode) {
            $plans[$planCode] = Plan::model()->findByPlanCode($planCode)->name;
        }

        return $plans;
    }
}