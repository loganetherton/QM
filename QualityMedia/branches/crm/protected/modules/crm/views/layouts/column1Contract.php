<?php $this->beginContent('/layouts/main'); ?>
    <div class="container-fluid" style='padding-left: 1%'>
        <div class="row-fluid">
            <div class="span12 full-content" id="parent-content">
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