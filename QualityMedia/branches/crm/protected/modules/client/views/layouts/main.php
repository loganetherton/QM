<!DOCTYPE HTML>
<html lang="en-US" class="torilaure">
<head>
    <meta charset="UTF-8">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="<?php echo $this->resourceUrl('css/font-awesome.min.css', 's3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('css/entypo.css', 's3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('fuelux/css/fuelux.css','s3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('css/client.css', 's3'); ?>" rel="stylesheet" media="screen">
    <link href="<?php echo $this->resourceUrl('css/client-hendra.css', 's3'); ?>" rel="stylesheet" media="screen">
    
    <script type="text/javascript" src="<?php echo $this->resourceUrl('assets/client/js/canvasjs.js'); ?>" charset="utf-8"></script>
</head>



<body>
    <?php $this->renderPartial('/layouts/_menu'); ?>

    <div class="container">
        <?php
            $this->widget('bootstrap.widgets.TbAlert', array(
                    'block'=>true, // display a larger alert block?
                    'fade'=>true, // use transitions?
                    'closeText'=>'×', // close link text - if set to false, no close link is displayed
                    'alerts'=>array( // configurations per alert type
                    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
                    'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
                ),
          ));

            echo $content;
        ?>
    </div>
</body>
</html>