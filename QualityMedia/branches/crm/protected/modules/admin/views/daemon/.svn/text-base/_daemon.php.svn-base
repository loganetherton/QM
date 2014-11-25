<div class="daemonView <?php echo strtolower($data->status); ?>">
    <div class="fLeft">
        <h5>
            <span><?php echo $data->getName(); ?></span>
            <?php echo CHtml::link($data->name, array('daemon/view', 'id'=>$data->id)); ?>
        </h5>

        <p class="description"><?php echo $data->getDescription(); ?></p>
    </div>

    <div class="details fRight">
        <?php echo Yii::app()->getComponent('format')->formatDateTime($data->createdAt); ?>
    </div>
</div>