<?php

$notifications = $model->accountManagerScope(Yii::app()->user->getId())->nonArchived()->search();
$notifications->setPagination(false);

 $this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider'          => $notifications,
    'type'                  => 'bordered striped condensed',
    'id'                    => 'snTable',
    'template'              => '{items}',
    'itemsCssClass'         => 'oview',
    'rowCssClassExpression' => '!$data->isRead() ? "bold" : null',
    'enableSorting'         => false,
    'columns'=>array(
        'type',
        'updatedAt:dateTime',
        array(
            'name'  => 'id',
            'type'  => 'raw',
            'header'=> 'Reason for Failing',
            'value' => '!empty($data->url) ? CHtml::link($data->content, array($data->url), array("class"=>"notification-archive")) : $data->content;',
        ),
        array(
            'name'  => 'id',
            'type'  => 'raw',
            'header'=> '&nbsp;',
            'value' => 'CHtml::link("&times;", array("hide", "id"=>$data->id), array("rel"=>$data->id, "class"=>"notification-archive"));',
        ),
    ),
));


//mark as read after load
 Yii::app()->getComponent('systemNotification')->markNotificationsAsRead(Yii::app()->getUser()->id);