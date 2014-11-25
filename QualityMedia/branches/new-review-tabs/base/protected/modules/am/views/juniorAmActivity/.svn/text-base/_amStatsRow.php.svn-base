<?php
    //Set the parent model filters
    $data->getDbCriteria()->mergeWith($parentModelCriteria);

    $pageVar = sprintf('ClientsTablePage_%s', $data->accountManager->id);

    $servicedClients = $data->getManagerServicedClients();
    $servicedClients->getPagination()->setPageSize(20);
    $servicedClients->getPagination()->forceFirstPageVar = true;
    $servicedClients->getPagination()->pageVar = $pageVar;
?>
<tr>
    <td><?php echo $data->accountManager->getFullName(", "); ?></td>
    <td><?php echo $data->publicCommentCount; ?></td>
    <td><?php echo $data->privateMessageCount; ?></td>
    <td><?php echo $data->flagCount; ?></td>
    <td class="expand show-review">
        <a href="#">
            <span class="text">Expand</span> <i class="icon3-sort"></i>
        </a>
    </td>
</tr>
<tr class="tb-child" data-id="<?php echo $data->accountManager->id; ?>" id="<?php echo $pageVar; ?>">
    <td colspan="5">
        <h5>Serviced Clients:</h5>
            <div class="span9">
        <?php

        $this->widget('bootstrap.widgets.TbGridView', array(
            'id'=>'serviced-clients-'.$data->accountManager->id,
            'dataProvider'=> $servicedClients,
            'type'=>'bordered',
            'template'=>'{items}{pager}',
            'htmlOptions'=>array('style'=>'padding-top:0px', 'class'=>'span12'),
            'itemsCssClass'=> 'oview',
            'enableSorting'=> false,
            //@Note: it should go via ajax reloading
            'ajaxUpdate' => false,
            'columns'=>array(
                'user.billingInfo.companyName',
                array(
                    'name'  => 'publicCommentCount',
                    'value' => '$data->publicCommentCount'
                ),
                array(
                    'name'  => 'privateMessageCount',
                    'value' => '$data->privateMessageCount'
                ),
                array(
                    'name'  => 'flagCount',
                    'value' => '$data->flagCount'
                ),
            ),
        ));

        ?>
            </div>
    </td>
</tr>