<?php
/**
 * Photos index view
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

$this->setPageTitle('View Photos');
$this->renderPartial('/layouts/tabs/client', array('active' => 'photos', 'photos' => true, 'id' => $id));
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span12">
        <a class="add-photo btn btn-large btn-info" style="margin-bottom: 10px;"><i class="icon-plus icon-white"></i> Upload new photo</a>
        <div class="photo-form" style="display: none; margin-top: 10px;">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'method' => 'post',
                'action' => $this->createUrl('photos/upload', array('id' => $id)),
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
            ));

            $photo = new Photo;

            echo $form->textArea($photo, 'caption', array('class' => 'input-block-level', 'placeholder' => 'type your caption here...'));
            echo CHtml::fileField('file', '', array('style' => 'line-height: 0px; height: 15px;'));?> <p class="line-base"></p> <?php
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Upload'));

            $this->endWidget();
            ?>
        </div>
        <div class="uploadedphoto">
            <ul>
            <?php
            foreach ($data->getData() as $photo)
            { ?>
                <li>
                    <div class="frphoto">
                    <?php
                    if (!$photo->isUploaded()) {
                    ?>
                        <p class="text-error" style="font-size: 1.5em; position:relative; top: 40%; text-align: center;">Pending</p>
                    <?php
                    }
                    else {
                    ?>
                        <img src="<?php echo $photo->photoUrl ?>" alt="" />
                    <?php
                    }
                    ?>
                    </div>
                    <?php
                    if ($photo->isFromOwner())
                    {
                        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                            'method' => 'post',
                            'action' => $this->createUrl('photos/update', array('id' => $photo->id)),
                        ));
                        echo $form->textArea($photo, 'caption', array('class' => 'input-block-level', 'placeholder' => 'type your caption here...'));
                        $this->widget('bootstrap.widgets.TbButton', array('size' => 'mini', 'buttonType' => 'submit', 'type' => 'success', 'label' => 'Update'));
                        echo '&nbsp;&nbsp;';
                        $this->widget('bootstrap.widgets.TbButton', array('size' => 'mini', 'buttonType' => 'link', 'type' => 'danger', 'label' => 'Delete', 'url' => $this->createUrl('photos/delete', array('id' => $photo->id))));

                        $this->endWidget();
                    }
                    else { ?>
                    <span class="from">From: <?php echo $photo->uploaderName ?></span>
                    <div class="caption">
                    <?php
                        if (!empty($photo->caption)) {
                            echo $photo->caption;
                        }
                    ?>
                    </div>
                    <?php
                        if (!$photo->isFlagged()) {
                    ?>
					        <a href="<?php echo $this->createUrl('photos/flag', array('id' => $photo->id)) ?>" class="flag">Flag as inappropriate</a>
                    <?php
                        }
                    }
                    ?>
                </li>
            <?php
            } ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
    var max_caption_height = 0;
    jQuery('.caption').each(function()
    {
        if (jQuery(this).height() > max_caption_height)
            max_caption_height = jQuery(this).height();
    });
    jQuery('.caption').height(max_caption_height);
    jQuery('.uploadedphoto ul li').height(260 + (max_caption_height - 35));
    jQuery('a.add-photo').click(function()
    {
        jQuery('.photo-form').toggle();
    });
</script>