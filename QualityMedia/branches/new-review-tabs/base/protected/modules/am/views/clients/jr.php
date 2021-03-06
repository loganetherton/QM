<?php
    $this->setPageTitle('Manage Clients');
    $this->renderPartial('/layouts/tabs/clientJr', array('active' => 'index', 'accountManagerId' => $accountManager->id));

    $this->leftContent = sprintf('<div style="font-weight: bold; font-size: 12px" class="alert alert-success">Reviewing %s %s\'s accounts</div>', $accountManager->firstName, $accountManager->lastName);

    $data = $model->search();
?>

<div id="main-content" class="row-fluid">
    <div id="ch-content" class="span9">
        <?php
            $this->renderPartial('_linksJr',array('model'=>$model, 'accountManagerId' => $accountManager->id));
            $noteImage = $this->resourceUrl('images/note.png', 's3');

            $this->widget('ClientGridView', array(
                'id'=>'clientList',
                'dataProvider'=>$data,
                'type'=>'bordered',
                'template'=>'{items}{pager}',
                'enablePagination'=>true,
                'rowCssClassExpression'=>'$data->isFlagged() ? "error" : ""',
                'rowHtmlOptionsExpression'=>'array("id" => "client-" . $data->id)',
                'columns'=>array(
                    array(
                        'name'  => 'add-notes',
                        'value' => 'CHtml::link("<img src=\"' . $noteImage . '\" alt=\'Add Notes\' />", "#", array("data-id" => $data->id))',
                        'type'  => 'raw',
                        'header' => 'Add Note',
                        'htmlOptions' => array(
                            'class' => 'add-note',
                            'style' => 'text-align: center;',
                        ),
                    ),
                    array(
                        'name'  => 'companyName',
                        'value' => '$data->billingInfo->companyName',
                        'header' => 'Client',
                        'headerHtmlOptions' => array(
                            'width' => '20%',
                        ),
                    ),
                    array(
                        'name'  => 'createdAt',
                        'value' => 'date("m/d/y", strtotime($data->createdAt))',
                    ),
                    array(
                        'class' => 'ClientDataColumn',
                        'name'  => 'reviewsCount',
                        'value' => '!empty($data->profile) && !empty($data->profile->yelpUsername) ? $data->profile->yelpReviewsCount : "Yelp account not linked"',
                        'header' => 'Yelp Reviews <span class="caret"></span>',
                        'htmlOptions' => array(
                            'colspan' => 'empty($data->profile->yelpUsername) ? 5 : 1',
                            'style'   => 'empty($data->profile) || empty($data->profile->yelpUsername) ? "text-align: center;" : ""'
                        ),
                        'evaluateHtmlOptions' => true,
                    ),
                    array(
                        'class' => 'ClientDataColumn',
                        'name'  => 'businesses',
                        'value' => array($this,'renderBusinessesColumn'),
                        'type'  => 'raw',
                        'header' => 'Yelp Profiles',
                        'showExpression' => '!empty($data->profile->yelpUsername)',
                    ),
                    array(
                        'class' => 'ClientDataColumn',
                        'name'  => 'activityLink',
                        'value' => array($this,'renderActivityColumn'),
                        'type'  => 'raw',
                        'header' => 'Activity Stats',
                        'showExpression' => '!empty($data->profile->yelpUsername)',
                    ),
                    array(
                        'class' => 'ClientDataColumn',
                        'name'  => 'infoLink',
                        'value' => array($this,'renderBusinessInfoColumn'),
                        'type'  => 'raw',
                        'header' => 'Business Info',
                        'showExpression' => '!empty($data->profile->yelpUsername)',
                    ),
                    array(
                        'class' => 'ClientDataColumn',
                        'name'  => 'photosLink',
                        'value' => array($this,'renderPhotosColumn'),
                        'type'  => 'raw',
                        'header' => 'Photos',
                        'showExpression' => '!empty($data->profile->yelpUsername)',
                    ),
                    array(
                        'name'  => 'notes',
                        'value' => '!empty($data->notes) ? CHtml::link("<span class=\'text\'>View</span>", "#", array("data-id" => $data->id)) : "View"',
                        'type'  => 'raw',
                        'header' => 'Notes',
                        'htmlOptions' => array(
                            'class' => 'notes-view',
                        ),
                    ),
                ),
            ));

            Yii::app()->getClientScript()->registerScript('tooltips', "
                $('.tooltipTrigger').popover({
                    'placement': 'top',
                    'trigger'  : 'hover',
                    'animation': false
                });
            ");
            $this->renderPartial('/notes/modal_jscript');

            foreach($data->getData() as $client) {
                $this->renderPartial('/notes/modalJr', array('data'=>$client, 'accountManagerId' => $accountManager->id,'type'=>Note::TYPE_CLIENT, 'page'=>'Client_page=' . (isset($_GET['Client_page']) ? $_GET['Client_page'] : 1)));
            }
        ?>
    </div>
    <div id="sd-bar" class="span3 accordion">
        <?php $this->renderPartial(
            '/layouts/partials/_sidebar',
            array(
                'model'=>$model,
                'listViewId' => 'clientList',
                'searchJs' => "
                jQuery('#Client_companyName,#Note_companyName').keyup(function() {
                    var el = jQuery(this);

                    if(el.val().length < 3) {
                        return false;
                    }

                    var form = el.parents('form:first');
                    ($.fn.yiiGridView || $.fn.yiiListView).update('clientList', {
                        data: form.serialize()
                    });
                });"
            )
        );

        if(Yii::app()->getUser()->isSenior()) {
            $seniorAmModel = Yii::app()->getUser()->getUser();
            $juniorAms = $seniorAmModel->getLinkedAccountManagers();

            $this->widget('JuniorAmsListView', array(
                'dataProvider' => $juniorAms,
                'itemView'     =>'/layouts/lists/_juniorAms',
                'viewData'     => array('linkSection' => '/am/clients/jr/')
            ));
        } ?>
    </div>
</div>