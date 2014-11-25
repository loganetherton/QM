<tr class="<?php echo $data->isArchived() ? 'warning' : ($data->isImportant() ? 'success' : ''); ?>">
    <td><span class="text"><?php echo !empty($data->user) ? CHtml::encode($data->user->billingInfo->companyName) : '<span style="font-style: italic;">N/A</span>' ?></span></td>
    <td>
        <span class="text">
            <?php echo date('m/d/Y', strtotime($data->createdAt)) ?>
        </span>
    </td>
    <td>
        <span class="text">
            <?php echo $data->isDue() ? date('m/d/Y', strtotime($data->dueAt)) : '<span style="font-style: italic;">N/A</span>' ?>
        </span>
    </td>
    <td class="expand">
        <a href="#" data-id="<?php echo $data->id ?>">
            <span class="text">Expand</span> <i class="icon3-sort"></i>
        </a>
    </td>
</tr>

<tr id="note-<?php echo $data->id ?>" class="tb-child" style="display: none;">
    <td colspan="4">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'method' => 'post',
            'action' => $this->createUrl('notes/update', array('id' => $data->id, 'redirect' => 'notes')),
        ));

        $data->dueAt = strtotime($data->dueAt) > 0 ? date('m/d/Y', strtotime($data->dueAt)) : '';

        echo CHtml::hiddenField('params', CJSON::Encode($_GET));

        echo $form->textArea($data, 'note', array('class' => 'input-block-level', 'placeholder' => 'type your note here...'));

        echo $form->datePickerRow($data, 'dueAt', array('label' => 'Due At:', 'id' => 'Note_dueAt_' . $data->id));?><br /><?php
        echo $form->label($data, 'important', array('label' => 'Mark as important', 'style' => 'display: inline; vertical-align: top'));?>: <?php
        echo $form->checkBox($data, 'important');?> <p class="line-base"></p> <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Update'));

        echo '&nbsp;&nbsp;';
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'link', 'type' => $data->isArchived() ? 'info' : 'warning', 'label' => $data->isArchived() ? 'Unarchive' : 'Archive', 'url' => $this->createUrl('notes/' . ($data->isArchived() ? 'un' : '') . 'archive', array('id' => $data->id, 'redirect' => 'notes'))));

        $this->endWidget();
        ?>
    </td>
</tr>