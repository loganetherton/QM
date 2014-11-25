<?php
$user = Yii::app()->getUser();
$isGuest = Yii::app()->getUser()->getIsGuest();
$username = $user->getUser() === null ? '' : $user->getUser()->getFullName();

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand'=>CHtml::image($this->resourceUrl('images/logo-small.png', 's3'), 'Admin Dashboard'),
    'brandUrl'=>Yii::app()->homeUrl,
    'fixed' => false,
    'htmlOptions' => array('class' => 'navbar navbar-static-top navbar-inverse'),
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'encodeLabel'=>false,
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>$username, 'url'=>'#', 'visible'=>!$isGuest, 'items'=>array(
                    array('label'=>'Logout', 'url'=>array('session/delete')),
                )),
            ),
        ),
    )
));