<?php
$isGuest = Yii::app()->getUser()->getIsGuest();

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand'=>'Sales Rep.',
    'brandUrl'=>Yii::app()->homeUrl,
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Subscriptions', 'url'=>array('subscription/index'), 'visible'=>!$isGuest),
                array('label'=>'Plans', 'url'=>array('plan/index'), 'visible'=>!$isGuest),
                array('label'=>'Login', 'url'=>array('session/create'), 'visible'=>$isGuest),
                array('label'=>'Logout', 'url'=>array('session/delete'), 'visible'=>!$isGuest),
            ),
        ),
    )
));