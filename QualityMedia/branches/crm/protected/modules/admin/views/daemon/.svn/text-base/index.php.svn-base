<?php $this->setPageTitle('Active Daemons'); ?>

<?php $this->renderPartial('/layouts/_tabs/daemon', array('active'=>'Daemons Overview')); ?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <h3>Active Daemons</h3>

        <?php
            $this->widget('bootstrap.widgets.TbListView', array(
                'id'=>'active-daemons',
                'dataProvider'=>$model->search(),
                'template'=>'{items}',
                'emptyText'=>'No daemon is running',
                'itemView'=>'_daemon',
            ));
        ?>
    </div>
</div>