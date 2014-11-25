<?php $this->setPageTitle(sprintf('View %s log', $model->name)); ?>

<?php $this->renderPartial('/layouts/_tabs/daemon', array('active'=>'Daemon Logs')); ?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <h3>
            <?php echo CHtml::encode($model->name); ?>
            <span class="label <?php echo $model->status; ?>"><?php echo $model->getStatus(); ?></span>
        </h3>

        <?php
            $this->widget('bootstrap.widgets.TbDetailView', array(
                'data'=>$model,
                'attributes'=>array(
                    'name',
                    'host',
                    'pid',
                    'argv',
                    'createdAt:datetime:Started',
                    'updatedAt:relativeDatetime:Seen',
                ),
            ));
        ?>

        <hr /><br />

        <h3>Daemon Events</h3>

        <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'daemon-events',
                'dataProvider'=>$model->getEvents(),
                'template'=>'{items}{pager}',
                'enableSorting'=>false,
                'type'=>'striped',
                'itemsCssClass'=>'daemonEvents',
                'htmlOptions'=>array(
                    'class'=>'',
                ),
                'columns'=>array(
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