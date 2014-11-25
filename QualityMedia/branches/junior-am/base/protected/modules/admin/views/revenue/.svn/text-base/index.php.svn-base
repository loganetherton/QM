<?php $this->setPageTitle('Revenue & Metrics overview'); ?>
<?php
    $totalSubscriptions = $model->getTotalSubscriptions();
    $totalActiveSubscriptions = $model->getTotalSubscriptions(true);
    $totalCanceledSubscriptions = $totalSubscriptions - $totalActiveSubscriptions;

    $attritionRate = 0;
    if($totalSubscriptions) {
        $attritionRate = $totalCanceledSubscriptions / $totalSubscriptions * 100;
    }
?>
<?php $this->renderPartial('/layouts/_tabs/revenues', array('active'=>'Overview')); ?>
<div id="main-content">
    <div class="span12 row-fluid">
        <div class="row-fluid">
            <div class="span12 box-function">
                <div class="counting-point clearfix">
                    <div class="cp1">
                        Total Subscriptions: <br /><span><?php echo $totalActiveSubscriptions; ?></span>
                    </div>
                    <div class="cp1">
                        Total Revenues to date: <br /><span>$<?php echo $model->getTotalRevenues(); ?></span>
                    </div>
                    <div class="cp1">
                        Client Attrition Rate: <br /><span><?php echo number_format($attritionRate, 2); ?>%</span>
                    </div>
                    <div class="cp2">
                        <span><?php echo $totalCanceledSubscriptions; ?></span> cancellations
                        to date
                    </div>
                </div>
            </div>
        </div>
        <p class="space-1"></p>

        <div class="row-fluid">
            <div class="span-12">
                <div id="ch-content" class="span6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="top1">Entire History</th>
                                <th class="top1 t_right">Sales Reps with Most Signups</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $salesReps = $model->getTopSalesRepsBySignups(null); ?>
                            <?php if(count($salesReps) == 0): ?>
                                <tr>
                                    <td colspan="2" class="expand c-blue">No results found</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1;
                                    $salesReps = array_chunk($salesReps, 10);
                                    $salesReps = $salesReps[0];
                                ?>
                                <?php foreach($salesReps as $salesRep): ?>
                                    <tr>
                                        <td class="c-blue"><?php printf('%d. %s', $i++, $salesRep->getFullName(', ')); ?></td>
                                        <td class="expand c-blue"><?php echo $salesRep->signups; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div id="ch-content" class="span6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="top1">Entire history</th>
                                <th class="top1 t_right">Sales Reps with Lowest Attrition</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $subscriptions = $model->getLowestAttrition(); ?>
                            <?php if(count($subscriptions) == 0): ?>
                                <tr>
                                    <td colspan="2" class="expand c-blue">No results found</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1;
                                    $subscriptions = array_chunk($subscriptions, 10);
                                    $subscriptions = $subscriptions[0];
                                ?>
                                <?php foreach($subscriptions as $subscription): ?>
                                    <tr>
                                        <td class="c-blue"><?php printf('%d. %s', $i++, $subscription->user->salesman->getFullName(', ')); ?></td>
                                        <td class="expand c-blue"><?php echo number_format($subscription->attrition, 2); ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <p class="space"></p>

        <div class="row-fluid">
            <div id="ch-content" class="span12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="top1">Subscription Growth for 6-month period</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="margin: 0; padding: 0; text-align: center">
                                <?php $this->renderPartial('content/_growthChart', array('model'=>$model)); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>