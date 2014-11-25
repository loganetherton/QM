<?php
echo CHtml::encode($this->renderPartial('/layouts/tabs/contractEntry', array(
    'active' => 'contractsLive',
    'activity' => true,
)));
$this->pageTitle = 'Saved Contracts';
?>
<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <?php
        $columns = array(
            array(
                'name' => 'companyName',
                'header' => 'Client Name',
            ),
            array(
                'name' => 'contractDate',
                'header' => 'Contract Date',
                'type' => 'raw',
                'value' => 'Yii::app()->getComponent("format")->formatDate($data->contractDate)',
            ),
        );
        $this->widget(
            'bootstrap.widgets.TbGridView',
            array(
                'type' => 'bordered condensed striped',
                'id' => 'contracts',
                'dataProvider' => $model->search(),
                'columns' => $columns,
                'template'  => "{items}\n{pager}",

            )
        );
        ?>
    </div>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'method' => 'post',
        'action' => $this->createUrl('contract/live'),
    ));
    ?>
    <div id="ch-sidebar" class="span3">
        <div class="accordion" id="accordion2">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                        Filter by Client <b class="caret pull-right"></b>
                    </a>
                </div>
                <div id="collapseTwo" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <?php
                        echo $form->textField($model, 'companyName', array('class' => 'input-block-level'));

                        $this->widget(
                            'bootstrap.widgets.TbButton',
                            array(
                                'buttonType'=>'submit',
                                'label' => 'Search',
                                'type' => 'primary',
                                'htmlOptions' => array('class' => 'btn-mini'),
                            ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
                        Filter by Date <b class="caret pull-right"></b>
                    </a>
                </div>
                <div id="collapseThree" class="accordion-body collapse">
                    <div class="accordion-inner daterange">
                        <?php
                        echo $form->dateRangeRow(
                                $model,
                                'contractDateRange',
                                array(
                                    'class' => 'input-block-level',
                                    'label' => false,
                                )
                            );
                        $this->widget(
                            'bootstrap.widgets.TbButton',
                            array(
                                'buttonType'=>'submit',
                                'label' => 'Search',
                                'type' => 'primary',
                                'htmlOptions' => array('class' => 'btn-mini'),
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $this->endWidget();
    ?>
</div>