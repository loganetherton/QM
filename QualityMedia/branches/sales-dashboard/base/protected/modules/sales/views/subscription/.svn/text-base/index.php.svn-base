<?php $this->setPageTitle('Clients List');

$listTemplate = '
<div class="container-padding radios">
    <label class="radio span2">
        <input type="radio" name="radio1" checked="">
        W/ Invoice Failed
        <small>With latest invoice failed</small>

    </label>
    <label class="radio span2">
        <input type="radio" name="radio1" checked="">
        W/ Invoice PastDue
        <small>With latest invoice past due</small>

    </label>
    <label class="radio span2">
        <input type="radio" name="radio1" checked="">
        All Active
        <small>All active, includes trials, future</small>

    </label>
    <label class="radio span2">
        <input type="radio" name="radio1" checked="">
        Canceled
        <small>Recently cancelled, will not renew</small>

    </label>
    <label class="radio span2">
        <input type="radio" name="radio1" checked="">
        Terminated
        <small>Old non-active subscriptions</small>

    </label>

    <div class="clear"></div>
</div>
{items}
{pager}
';

$this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $model->search(),
        'itemView' => '_client',
        'template' => $listTemplate,
        'summaryText' => '',
    )
);