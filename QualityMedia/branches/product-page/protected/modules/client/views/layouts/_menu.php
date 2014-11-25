<?php
$isGuest = Yii::app()->getUser()->getIsGuest();

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand'=>'Dashboard',
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Profile', 'url'=>array('user/view'), 'visible'=>!$isGuest),
                array('label'=>'Login', 'url'=>array('session/create'), 'visible'=>$isGuest),
                array('label'=>'Logout', 'url'=>array('session/delete'), 'visible'=>!$isGuest),
            ),
        ),
    )
));