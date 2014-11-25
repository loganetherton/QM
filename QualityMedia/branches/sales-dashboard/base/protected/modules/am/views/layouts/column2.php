<?php
$controllersConnection = array(
    'review'    => 'Reviews',
    'message'   => 'Social Messaging',
    'clients'   => 'Manage Clients',
    'activity'  => 'Manage Clients',
    'photos'    => 'Manage Clients',
    'manage'    => 'Manage Clients',
    'notes'     => isset($_GET['type']) && $_GET['type'] == 'review' ? 'Reviews' : 'Manage Clients',
    'settings'  => 'Settings',
);

$menuItems = array(
    'Reviews' => array('label'=>'Reviews', 'url'=>array('review/index', 'tab'=>'opened'), 'icon'=>'icon3-folder-close', 'linkOptions'=>array('class'=>'clearfix')),
    'Social Messaging' => array('label'=>'Private Messages', 'url'=>array('message/index', 'tab'=>'inbox'), 'icon'=>'icon3-comments', 'linkOptions'=>array('class'=>'clearfix')),
    'Manage Clients' => array('label' => 'Manage Clients', 'url' => array('clients/index'), 'icon' => 'icon3-user', 'linkOptions' => array('class' => 'clearfix')),
    'Settings' => array('label'=>'My Settings', 'url'=>array('settings/update'), 'icon'=>'icon3-cog', 'linkOptions'=>array('class'=>'clearfix')),
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
                        'block'=>true,
                        'fade'=>true,
                        'closeText'=>'×',
                        'alerts'=>array(
                            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'),
                            'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'),
                        ),
                    ));

                    echo $content;
                ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>