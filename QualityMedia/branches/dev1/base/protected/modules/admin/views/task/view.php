<?php $this->setPageTitle(sprintf('View %s task', $model->taskName)); ?>

<?php $this->renderPartial('/layouts/_tabs/daemon', array('active'=>'Daemon Task')); ?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <h3><?php echo CHtml::encode(sprintf('Task %d (%s)', $model->id, $model->taskName)); ?></h3>

        <?php
            $this->widget('bootstrap.widgets.TbDetailView', array(
                'data'=>$model,
                'attributes'=>array(
                    array(
                        'name'  => 'status',
                        'value' => $model->getStatus(),
                    ),
                    'taskName',
                    array(
                        'name'  => 'leaseExpires',
                        'label' => 'Lease Status',
                        'value' => $model->getLeaseStatus(),
                    ),
                    'leaseOwner',
                    'failureCount',
                    'data',
                ),
            ));
        ?>

        <hr /><br />

        <h3>Error History</h3>

        <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'task-error-history',
                'dataProvider'=>$model->getErrorHistory(),
                'template'=>'{items}{pager}',
                'enableSorting'=>false,
                'type'=>'striped',
                'itemsCssClass'=>'daemonEvents',
                'emptyText'=>'No error history for this task',
                'htmlOptions'=>array(
                    'class'=>'',
                ),
                'columns'=>array(
                    'content:text:Error',
                    'createdAt:relativeDatetime:Occured At',
                ),
            ));
        ?>
    </div>
</div>