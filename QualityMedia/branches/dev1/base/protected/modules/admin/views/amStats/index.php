<?php
    $this->setPageTitle('Account Manager Stats');
    $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Account Managers Stats'));

    $model->junior();
    $modelDbCriteria = $model->getDbCriteria();
    $dataProvider = $model->search();

    //Parse Serviced clients pagination data

    $clientsTablesPages = array_map(
        function($value) {
            return '#'.$value;
        },
        array_filter(
            array_keys($_GET),
            function($value) {
                return (substr($value, 0, 17) == 'ClientsTablePage_');
            }
        )
    );

    $clientsTablesPagesSelector = implode(', ', $clientsTablesPages);
?>
<input type="hidden" name="clientsTablesPagesSelector" id="clientsTablesPagesSelector" value="<?php echo $clientsTablesPagesSelector; ?>" />

<div id="main-content">
    <div class="span12 row row-fluid gran-data">
        <?php

        /** @var TbActiveForm $form */
        $form = $this->beginWidget(
            'bootstrap.widgets.TbActiveForm',
            array(
                'id' => 'verticalForm',
                'method' => 'get',
                'htmlOptions' => array('class' => 'well'), // for inset effect
                'action' => array('/admin/amStats'), // for inset effect
            )
        );
        ?>
            <div class="span4">
                <?php
                    echo $form->radioButtonListRow($model, 'reviewTypeFilter', array(
                        'All reviews',
                        'Precontract reviews',
                        'Post contract reviews',
                    ));
                ?>
            </div>
            <div class="span4">
                <?php
                    echo $form->dateRangeRow(
                        $model,
                        'dateRange',
                        array(
                            'prepend' => '<i class="icon-calendar"></i>',
                            'options' => array(
                                'format' => 'dd-MM-yyyy',
                                'callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}',
                            ),
                            'autocomplete'=>'off'
                        )
                    );
                ?>
            </div>
            <div class="span12">
                <?php
                    $this->widget(
                        'bootstrap.widgets.TbButton',
                        array('buttonType' => 'submit', 'label' => 'Filter', 'type' => 'primary')
                    );
                ?>
            </div>
            <br style="clear: both" />
        <?php
        $this->endWidget();

        $headers = array('Full&nbsp;Name', 'Public&nbsp;Comment&nbsp;Count', 'Private&nbsp;Message&nbsp;Count', 'Flag&nbsp;Count', '');

        $this->widget('AmStatsListView', array(
            'id'=>'statsGrid',
            'dataProvider' => $dataProvider,
            'itemView'     => '_amStatsRow',
            'viewData'     => array(
                'formatter' => Yii::app()->getComponent('format'),
                'parentModel' => $model,
                'parentModelCriteria' => $modelDbCriteria
            ),
            'enableSorting'   => false,
            'headers'         => $headers,
            //@Note: it should go via ajax reloading
            // 'afterAjaxUpdate' =>'js:function(id, data){ statsGrid(); expandedRows = jQuery("#clientsTablesPagesSelector", data).val()}',
            'ajaxUpdate' => false,
        ));

        Yii::app()->getClientScript()->registerScript('amstatsListView', "
            var expandedRows = '".$clientsTablesPagesSelector."';

            function statsGridInit() {
                jQuery('.expand.show-review a').live('click', function(e) {
                    e.preventDefault();

                    var reviewRow = jQuery(this).parent().parent();
                    var isUnread = reviewRow.hasClass('bold');

                    if(reviewRow.hasClass('on')) {
                        reviewRow.removeClass('on');
                        reviewRow.next().hide();
                        jQuery(this).children('.text').html('Expand');
                    }
                    else {
                        reviewRow.addClass('on');
                        reviewRow.next().show();
                        jQuery(this).children('.text').html('Close');
                    }
                });

            }

            function statsGrid()
            {
                jQuery('.tb-child').hide();
                jQuery(expandedRows).show();
            }

            statsGridInit();
            statsGrid();
        ");
?>
    </div>
</div>