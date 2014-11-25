<?php $this->setPageTitle('Daemon Tasks'); ?>

<?php $this->renderPartial('/layouts/_tabs/daemon', array('active'=>'Daemon Tasks')); ?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <h3>Queued Tasks</h3>

        <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'daemon-queued-tasks',
                'dataProvider'=>$model->getQueuedTasks(),
                'template'=>'{items}{pager}',
                'enableSorting'=>false,
                'type'=>'striped',
                'itemsCssClass'=>'daemonEvents',
                'htmlOptions'=>array(
                    'class'=>'',
                ),
                'columns'=>array(
                    'taskName',
                    'count',
                ),
            ));
        ?>

        <hr /><br />

        <h3>Leased Tasks</h3>

        <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'leased-tasks',
                'dataProvider'=>$model->getLeasedTasks(),
                'template'=>'{items}{pager}',
                'enableSorting'=>false,
                'type'=>'striped',
                'itemsCssClass'=>'daemonEvents',
                'emptyText'=>'No tasks are leased by workers',
                'htmlOptions'=>array(
                    'class'=>'',
                ),
                'columns'=>array(
                    'id',
                    'taskName',
                    'leaseOwner',
                    'leaseExpires:datetime',
                    'failureCount',
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template'=>'{view}',
                    ),
                ),
            ));
        ?>
    </div>
</div>