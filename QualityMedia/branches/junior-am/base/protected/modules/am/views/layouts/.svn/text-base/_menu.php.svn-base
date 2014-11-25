<?php
$user = Yii::app()->getUser();
$isGuest = $user->getIsGuest();
$username = $user->getUser() === null ? '' : $user->getUser()->getFullName();

//user notifications
$user->setSystemNotificationsCount(AccountManager::model()->getSystemNotificationsCount($user->getId()));

$systemNotificationsLabel = sprintf('System Notifications <span id="notifications-counter" class="badge badge-important">%d</span>', $user->getSystemNotificationsCount());;
$reviewsLabel  = sprintf('Unviewed Reviews <span id="reviews-counter" class="badge badge-important">%d</span>', $user->getReviewsCount());
$messagesLabel = sprintf('Unviewed Private Messages <span id="messages-counter" class="badge badge-important">%d</span>', $user->getMessagesCount());

$this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse',
    'brand'=>CHtml::image($this->resourceUrl('images/logo-small.png', 's3'), 'Admin Dashboard'),
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
                array('label'=>$systemNotificationsLabel, 'icon'=>'icon3-envelope-alt', 'url'=>array('#', 'tab'=>'inbox'), 'visible'=>!$isGuest, 'linkOptions' => array('class' => 'showSystemNotifications', 'data-toggle'=> 'modal', 'data-target' => '#systemNotificationsModal')),
                array('label'=>$reviewsLabel, 'icon'=>'icon3-rss', 'url'=>array('review/index', 'tab'=>'opened'), 'visible'=>!$isGuest),
                array('label'=>$messagesLabel, 'icon'=>'icon3-envelope-alt', 'url'=>array('message/index', 'tab'=>'inbox'), 'visible'=>!$isGuest),
                '---',
                array('label'=>$username, 'url'=>'#', 'visible'=>!$isGuest, 'items'=>array(
                    array('label'=>'Logout', 'url'=>array('session/delete')),
                )),
            ),
        ),
    ),
));

unset($user, $isGuest, $username, $reviewsLabel, $messagesLabel);