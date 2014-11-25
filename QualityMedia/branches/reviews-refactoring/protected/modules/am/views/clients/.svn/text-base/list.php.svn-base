<?php
/**
 * Client list view
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

$this->setPageTitle('Client list');
?>

    <div id="main-content" class="row-fluid gran-data">
        <div id="ch-content" class="span9">
            <h2><i class="icon3-user"></i> Client list</h2>
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $data,
    'type' => 'bordered',
    'template' => '{items}{pager}',
    'id' => 'clientList',
    'enablePagination' => true,
    'rowCssClassExpression' => '$data->isFlagged() ? "error" : ""',
    'columns' => array(
        array(
            'name' => 'companyName',
            'value' => '$data->billingInfo->companyName',
            'header' => 'Client',
        ),
        array(
            'name' => 'reviewsCount',
            'value' => '$data->profile->yelpReviewsCount',
            'header' => 'Yelp Reviews',
        ),
        array(
            'name' => 'createdAt',
            'value' => '$data->createdAt',
        ),
    ),
));

?>
        </div>
        <div id="ch-sidebar" class="span3">

<?php
            $this->renderPartial('/clients/_sidebar', array('model' => $model));
?>

        </div>
    </div>
