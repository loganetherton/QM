<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'method' => 'post',
        'action' => $this->createUrl($data->getIsNewRecord() ? 'notes/create' : 'notes/update', array('id' => $data->getIsNewRecord() && !empty($am) ? $am : $data->id, 'redirect' => urlencode('notes/index/type/' . $type), 'type' => $type)),
    ));

    $data->dueAt = strtotime($data->dueAt) > 0 ? date('m/d/Y', strtotime($data->dueAt)) : '';

    echo CHtml::hiddenField('params', CJSON::Encode($_GET));

    echo $form->label($data, 'subject', array('label' => 'Subject:'));
    echo $form->textField($data, 'subject', array('class' => 'input-block-level'));

    echo $form->label($data, 'note', array('label' => 'Note\'s text:'));
    echo $form->textArea($data, 'note', array('class' => 'input-block-level', 'placeholder' => 'type your note here...'));

    echo $form->datePickerRow($data, 'dueAt', array('label' => 'Due At:', 'id' => $data->getIsNewRecord() && !isset($note_id) ? 'Note_dueAt' : ($data->getIsNewRecord() ? 'Note_dueAt_' . $note_id : 'Note_dueAt_' . $data->id)));?><br /><?php
    echo $form->label($data, 'important', array('label' => 'Mark as important', 'style' => 'display: inline; vertical-align: top'));?>: <?php
    echo $form->checkBox($data, 'important');?> <p class="line-base"></p> <?php
    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => $data->getIsNewRecord() ? 'Add Note' : 'Update'));

    echo '&nbsp;&nbsp;';
    if (!$data->getIsNewRecord())
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'link', 'type' => $data->isArchived() ? 'info' : 'warning', 'label' => $data->isArchived() ? 'Unarchive' : 'Archive', 'url' => $this->createUrl('notes/' . ($data->isArchived() ? 'un' : '') . 'archive', array('id' => $data->id, 'redirect' =>  urlencode('notes/index/type/' . $type)))));

    $this->endWidget();
?>