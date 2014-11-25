<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link href="<?php echo $this->resourceUrl('css/font-awesome.min.css', 's3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('css/entypo.css', 's3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('css/custom.css', 's3'); ?>" rel="stylesheet" media="screen">
    <link href="<?php echo $this->resourceUrl('bootstrap_v3/css/bootstrap-select.min.css', 's3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('bootstrap/css/bootstrap-datepicker.css', 's3'); ?>" rel="stylesheet">
    <?php
	// Link to the uploaded css on S3
    //dd($this->resourceUrl('css/contractCreate.css', 's3'));
    ?>
</head>
<body>
    <?php $this->renderPartial('/layouts/_menu'); ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <?php echo $content; ?>
        </div>
    </div>


    <?php
    
   // if(!Yii::app()->user->isGuest) {
     //   $this->renderPartial('/layouts/_systemNotifications');
   // }
    ?>

    <!-- Necessary? -->
    <script src="<?php echo $this->resourceUrl('js/jquery.raty.min.js', 's3'); ?>"></script>
</body>
</html>