<div class="container-padding post-ct">
    <div class="span4 tabl">
        <?php echo $data->user->billingInfo->companyName; ?>
    </div>
    <div class="span1 brd tabl ">
        <?php echo date('d/m/Y', strtotime($data->createdAt)); ?>
    </div>
    <div class="span4 brd tabl">
        <?php echo $data->getPlanDescription(); ?>
    </div>
    <div class="span1 brd tabl">
        <span class="clr-red">Failed</span>
    </div>
    <div class="span2 brd tbl-btn">
        <a href="<?php echo $this->createUrl('subscription/view/id/'.$data->id); ?>" class="btn">View Detail</a>
    </div>
    <div class="clear"></div>
</div>