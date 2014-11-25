<?php
$user = Yii::app()->user->getUser();
$isGuest = $user === null;
$username = $user === null ? '' : $user->getFullName(' ');

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand'=>CHtml::image($this->resourceUrl('images/logo-client.png','s3'), 'Client Dashboard').'<span class="title-logo">Client Dashboard</span>',
    'brandUrl'=>Yii::app()->homeUrl,
    'collapse'=>true,
    'fixed'=>false,
    'htmlOptions' => array('class'=>'navbar-fixed-top'),
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>'Login', 'url'=>array('session/create'), 'visible'=>$isGuest, 'linkOptions' => array('class'=>'blue')),
            ),
        ),
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>'Logout','url' =>array('session/delete'), 'visible'=>!$isGuest, 'linkOptions' => array('class'=>'blue')),
            ),
        ),
        
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'encodeLabel'=>false,
            'items'=>array(
                array('label'=>'Welcome, <b>'.$username .'</b>', 'url'=>'#', 'visible'=>!$isGuest, 'items'=>array(
                    array('label'=>'<i class="icon-cog"></i> Preferences', 'url'=>array('user/preferences'), 'class'=>'ico-cog'),
                    array('label'=>'<i class="icon-envelope"></i> Contact Support', 'url'=>'#'),
                )),
            ),
        ),
    ),
));