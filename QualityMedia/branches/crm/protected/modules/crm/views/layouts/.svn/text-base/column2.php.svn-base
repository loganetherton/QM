<?php
$controllersConnection = array(
    'contract' => 'New Contract',
);

$menuItems = array(
    'New Contract' => array('label'       => 'New Contract',
                            'url'         => array('contract/create'),
                            'icon'        => 'icon3-briefcase',
                            'linkOptions' => array('class'=>'clearfix')),
);

if(in_array($this->getId(), array_keys($controllersConnection))) {
    $menuItems[$controllersConnection[$this->getId()]]['itemOptions'] = array('class'=>'active');
}
?>
<?php $this->beginContent('/layouts/main'); ?>
    <div class="span2">
        <?php
            $this->widget('CustomTbMenu',array(
                'type'        => 'list',
                'encodeLabel' => false,
                'items'       => array_values($menuItems),
            ));

            //Custom Content
            if($this->leftContent) {
                echo $this->leftContent;
            }
        ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <div class="span12" id="parent-content">
                <?php
                    $this->widget('bootstrap.widgets.TbAlert', array(
                        'block'     => true,
                        'fade'      => true,
                        'closeText' => '×',
                        // Let the salesman know on success/error
                        'alerts' => array(
                            'success' => array('block' => true, 'fade' => true, 'closeText' => '×'),
                            'error'   => array('block' => true, 'fade' => true, 'closeText' => '×'),
                        ),
                    ));
                    echo $content;
                ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>