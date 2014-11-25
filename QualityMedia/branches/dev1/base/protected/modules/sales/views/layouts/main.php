<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <style type="text/css">
        body{margin:50px 0px 20px 0px;}
    </style>
</head>
<body>
    <?php $this->renderPartial('/layouts/_menu'); ?>

    <div class="container">
        <?php
                $this->widget('bootstrap.widgets.TbAlert', array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'×',
            ));

            echo $content;
        ?>
    </div>
</body>
</html>