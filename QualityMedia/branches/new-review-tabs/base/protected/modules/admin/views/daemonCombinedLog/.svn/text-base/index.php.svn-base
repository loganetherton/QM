<?php $this->setPageTitle('Combined Daemon Logs'); ?>

<?php $this->renderPartial('/layouts/_tabs/daemon', array('active'=>'Combined Logs')); ?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <h3>Combined Daemon Logs</h3>

        <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'combined-daemon-logs',
                'dataProvider'=>$model->search(100),
                'template'=>'{items}{pager}',
                'enableSorting'=>false,
                'type'=>'striped',
                'itemsCssClass'=>'daemonEvents',
                'rowCssClassExpression'=>array($this, 'evaluateRowCssClass'),
                'htmlOptions'=>array(
                    'class'=>'',
                ),
                'columns'=>array(
                    array(
                        'name'  => 'daemon',
                        'type'  => 'raw',
                        'value' => 'CHtml::link($data->daemon->getName(), array("daemon/view", "id"=>$data->daemonLogId))',
                    ),
                    'type',
                    'createdAt:relativeDatetime',
                    array(
                        'name'  => 'message',
                        'htmlOptions'=>array(
                            'class'=>'daemonEventsMessage',
                        ),
                    ),
                ),
            ));
        ?>
    </div>
</div>