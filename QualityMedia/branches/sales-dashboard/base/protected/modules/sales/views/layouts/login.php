<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link href="<?php echo $this->resourceUrl('css/font-awesome.min.css', 's3'); ?>" rel="stylesheet" />
    <link href="<?php echo $this->resourceUrl('css/sales-dashboard-custom.css', 's3'); ?>" rel="stylesheet" media="screen" />
</head>
<body class="sales-dash">
    <?php $this->renderPartial('/layouts/_menu'); ?>

    <div class="container-fluid" style="padding-left:10px;padding-right:10px">
        <?php
            $this->widget('bootstrap.widgets.TbAlert', array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'×',
            ));
        ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid parent-mc">
                    <div id="parent-content" style="padding:50px">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>