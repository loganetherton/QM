<?php
    $this->setPageTitle('Junior AM Activities');
    $this->renderPartial('/layouts/tabs/amStats', array('active'=>'index'));

    $this->actionTypesLabels = $model->getActionTypesLabels();
?>
<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
    <?php
        $this->renderPartial(
            '_search',
            array(
                'model'=>$model,
                'data'=> $data,
                'id'=>$id,
            )
        );
    ?>
    <br />

    <?php
        $listEmptyText = 'No results found.';

        //Show the list only if Junior Am is specified
        if(!$id) {
            $listEmptyText = 'Select Junior Manager from the list above';
            $data = new CActiveDataProvider('AmActivity', array('data'=>array()));
        }

        $data->getPagination()->setPageSize(20);

        $this->widget(
            'AmActivityListView',
            array(
                'type' => 'bordered condensed striped',
                'id' => 'activities',
                'dataProvider' => $data,
                'columns' => array(
                    array(
                        'name'   => 'status',
                        'header' => 'Status',
                        'type'   => 'raw',
                        'value'  => '"<img src=\"".$this->grid->owner->resourceUrl(sprintf("images/ico-%s.png", $this->grid->owner->statusIcons[$data->status]), "s3")."\" />"',
                    ),
                    array(
                        'name'   => 'type',
                        'header' => 'Action Type',
                        'type'   => 'raw',
                        'value'  => '$this->grid->owner->actionTypesLabels[$data->type].(!empty($data->comment) ? "<br /><span class=\"label label-important\">".$data->comment."</span>" : "").($data->status == Review::APPROVAL_STATUS_REJECTED ? "<br /><span class=\"label label-important\">Reverted</span>" : "")',
                    ),
                    array(
                        'name'   => 'user.billingInfo.companyName',
                        'header' =>  'Company Name',
                    ),
                    array(
                        'name'   => 'review.userName',
                        'header' =>  'Reviewer Name',
                    ),
                    array(
                        'name'   => 'review.reviewDate',
                        'header' => 'Review Date',
                        'type'   => 'raw',
                        'value'  => 'Yii::app()->getComponent("format")->formatDate($data->review->reviewDate).($data->review->deleted == 1 ? "<span class=\"label pull-right label-important\">Deleted</span>" : "")'
                    ),
                    'createdAt:datetime'
                ),
                'emptyText' => $listEmptyText,
                'template'  => "{totals}\n{items}\n{pager}",
            )
        );
    ?>
    </div>
    <div id="sd-bar" class="span3 accordion">
        <?php
        if(Yii::app()->getUser()->isSenior()) {
            $seniorAmModel = Yii::app()->getUser()->getUser();
            $juniorAms = $seniorAmModel->getLinkedAccountManagers();

            $this->widget('JuniorAmsListView', array(
                'dataProvider' => $juniorAms,
                'itemView'     =>'/layouts/lists/_juniorAms',
                'viewData'     => array('linkSection' => '/am/juniorAmActivity/'),
                'showJrActivitiesButton' => false
            ));
        } ?>
    </div>
</div>