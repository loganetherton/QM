<?php
$controllersConnection = array(
    'revenue'=>'Revenues & Metrics',
    'label'=>'Revenues & Metrics',
    'employee' => 'Employees',
    'accountManager' => 'Employees',
    'accountManagerPlan' => 'Employees',
    'salesman' => 'Employees',
    'salesmanPlan' => 'Employees',
    'salesmanAssignment' => 'Clients',
    'adminManagement' => 'Employees',
    'clientOverview'=>'Clients',
    'client'=>'Clients',
    'billingOverview'=>'Billing Management',
    'subscription'=>'Billing Management',
    'plan'=>'Billing Management',
    'transaction'=>'Billing Management',
    'clientSubscription'=>'Billing Management',
);

$menuItems = array(
    'Revenues & Metrics' => array('label'=>'Revenues & Metrics', 'url'=>array('revenue/index'), 'icon'=>'icon3-time', 'linkOptions'=>array('class'=>'clearfix')),
    'Employees' => array('label'=>'Employees', 'url'=>array('employee/index'), 'icon'=>'icon3-user', 'linkOptions'=>array('class'=>'clearfix')),
    'Clients' => array('label'=>'Clients', 'url'=>array('clientOverview/index'), 'icon'=>'icon3-globe', 'linkOptions'=>array('class'=>'clearfix')),
    'Billing Management' => array('label'=>'Billing Management', 'url'=>array('billingOverview/index'), 'icon'=>'icon3-folder-open', 'linkOptions'=>array('class'=>'clearfix'))
);

if(in_array($this->getId(), array_keys($controllersConnection))) {
    $menuItems[$controllersConnection[$this->getId()]]['itemOptions'] = array('class'=>'active');
}
?>
<?php $this->beginContent('/layouts/main'); ?>
    <div class="span2">
        <?php
            $this->widget('CustomTbMenu',array(
                'type'=>'list',
                'encodeLabel'=>false,
                'items'=>array_values($menuItems),
            ));
        ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <div class="span12" id="parent-content">
                        <?php
            $this->widget('bootstrap.widgets.TbAlert', array(
                    'block'=>true, // display a larger alert block?
                    'fade'=>true, // use transitions?
                    'closeText'=>'×', // close link text - if set to false, no close link is displayed
                    'alerts'=>array( // configurations per alert type
                    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
                ),
            ));
        ?>
                <?php echo $content; ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>