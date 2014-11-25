<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link href="<?php echo $this->resourceUrl('css/font-awesome.min.css', 's3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('css/entypo.css', 's3'); ?>" rel="stylesheet">
    <link href="<?php echo $this->resourceUrl('css/custom.css', 's3'); ?>" rel="stylesheet" media="screen">
</head>
<body>
    <?php $this->renderPartial('/layouts/_menu'); ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <?php echo $content; ?>
        </div>
    </div>
    <?php
    if (!empty($_SESSION['due_review_notes']) || !empty($_SESSION['due_client_notes'])) {
        $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'notesDue', 'options' => array('show' => true)));
    ?>

            <div class="modal-header" style="padding: 20px 15px;">
                <a class="close" data-dismiss="modal">&times;</a>
            <?php if ($_SESSION['due_review_notes'] > 0): ?>
                <h4>You have <?php echo $_SESSION['due_review_notes'] ?> Review Note(s) due today!</h4>
            <?php endif; ?>
            <?php if ($_SESSION['due_client_notes'] > 0): ?>
                <h4>You have <?php echo $_SESSION['due_client_notes'] ?> Client Note(s) due today!</h4>
            <?php endif; ?>
            </div>

            <div class="modal-footer">
                <?php
                if ($_SESSION['due_review_notes'] > 0)
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'type' => 'info',
                        'label' => 'View Review Notes',
                        'url' => $this->createUrl('notes/index/type/review'),
                    ));
                if ($_SESSION['due_client_notes'] > 0)
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'type' => 'primary',
                        'label' => 'View Client Notes',
                        'url' => $this->createUrl('notes/index/type/client'),
                    ));
                ?>
            </div>

    <?php
        $this->endWidget();
        unset($_SESSION['due_review_notes']);
        unset($_SESSION['due_client_notes']);
    }
        if(!Yii::app()->user->isGuest) {
            $this->renderPartial('/layouts/_systemNotifications');
        }
    ?>

    <script src="<?php echo $this->resourceUrl('js/jquery.raty.min.js', 's3'); ?>"></script>
</body>
</html>