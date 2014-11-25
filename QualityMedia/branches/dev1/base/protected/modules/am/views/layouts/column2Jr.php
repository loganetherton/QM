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
    'Reviews' => array('label'=>'Reviews', 'url'=>array('review/jr', 'tab'=>'opened', 'id'=> $this->jrModel->id), 'icon'=>'icon3-folder-close', 'linkOptions'=>array('class'=>'clearfix')),
    'Social Messaging' => array('label'=>'Private Messages', 'url'=>array('message/jr', 'id'=> $this->jrModel->id, 'tab'=>'inbox'), 'icon'=>'icon3-comments', 'linkOptions'=>array('class'=>'clearfix')),
    'Manage Clients' => array('label' => 'Manage Clients', 'url' => array('clients/jr', 'id'=> $this->jrModel->id), 'icon' => 'icon3-user', 'linkOptions' => array('class' => 'clearfix')),
);
if(in_array($this->getId(), array_keys($controllersConnection))) {
    $menuItems[$controllersConnection[$this->getId()]]['itemOptions'] = array('class'=>'active');
}

$jrInfo = array(
    'review' => 'reviews',
    'message' => 'messages',
    'clients' => 'clients',
    'emailReport' => 'clients'
);
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
        <?php if(in_array($this->getId(), array_keys($jrInfo))): ?>
            <div style="font-weight: bold; font-size: 12px" class="alert alert-success">
                <?php echo sprintf('Reviewing %s %s\'s %s', $this->jrModel->firstName, $this->jrModel->lastName, $jrInfo[$this->getId()]); ?>
            </div>
        <?php endif; ?>
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