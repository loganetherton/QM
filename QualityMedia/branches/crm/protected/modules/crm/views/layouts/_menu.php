<?php
$user = Yii::app()->user;
$isGuest = $user->getIsGuest();
$username = $user->getUser() === null ? '' : $user->getUser()->getFullName(' ', false);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse',
    'brand'=>CHtml::image($this->resourceUrl('images/logo-small.png', 's3'), 'CRM Dashboard'),
    'brandUrl'=>Yii::app()->homeUrl,
    'collapse'=>true,
    'fixed'=>false,
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>'Login', 'url'=>array('session/create'), 'visible'=>$isGuest),
            ),
        ),
        '---',
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'encodeLabel'=>false,
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                '---',
                array('label'=> 'Welcome, ' . $username . '!', 'url'=>'#', 'visible'=>!$isGuest, 'items'=>array(
                    array('label'=>'Logout', 'url'=>array('session/delete')),
                )),
            ),
        ),
    ),
));

unset($user, $isGuest, $username, $reviewsLabel, $messagesLabel);