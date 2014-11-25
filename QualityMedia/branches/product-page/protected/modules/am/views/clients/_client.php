<tr class="tb-child" id="client-notes-<?php echo $data->id ?>" style="display: none;">
    <td colspan="7">
        <?php
            $note = !empty($data->notes) ? $data->notes[0] : new Note;
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'method' => 'post',
                'action' => $this->createUrl('notes/' . (!empty($note->id) ? 'update' : 'create'), array('id' => !empty($note->id) ? $note->id : $data->id)),
            ));

            $note->dueAt = strtotime($note->dueAt) > 0 ? date('m/d/Y', strtotime($note->dueAt)) : '';

            echo CHtml::hiddenField('params', CJSON::Encode($_GET));

            echo $form->textArea($note, 'note', array('class' => 'input-block-level', 'placeholder' => 'type your note here...'));

            echo $form->datePickerRow($note, 'dueAt', array('label' => 'Due At:', 'id' => 'Note_dueAt_' . $data->id));?><br /><?php
            echo $form->label($note, 'important', array('label' => 'Mark as important', 'style' => 'display: inline; vertical-align: top'));?>: <?php
            echo $form->checkBox($note, 'important');?> <p class="line-base"></p> <?php
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => !empty($note->id) ? 'success' : 'info', 'label' => !empty($note->id) ? 'Update' : 'Add Note'));

            if (!empty($note->id))
            {
                echo '&nbsp;&nbsp;';
                $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'link', 'type' => 'warning', 'label' => 'Archive', 'url' => $this->createUrl('notes/archive', array('id' => $note->id))));
            }

            $this->endWidget();
         ?>
    </td>
</tr>