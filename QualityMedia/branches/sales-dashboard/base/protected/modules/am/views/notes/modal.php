<?php
$redirect = $type == 'review' ? urlencode('review/' . $tab) : urlencode('clients/index');

if (isset($page)) {
    $redirect = $redirect . urlencode('/' . $page);
}

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'notes-' . $data->id));
?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">x</a>
<?php if ($type == Note::TYPE_REVIEW): ?>
        <h4>Notes: <em class="text-error"><?php echo $data->userName ?>'s</em> review of <em class="text-info"><?php echo $data->user->billingInfo->companyName ?></em></h4>
<?php else: ?>
        <h4>Notes: <em class="text-info"><?php echo $data->billingInfo->companyName ?></em></h4>
<?php endif; ?>
    </div>
    <div class="modal-body">
<?php foreach ($data->notes as $k => $note): ?>
        <div class="note" data-id="<?php echo $note->id ?>">
            <h5 class="text-info" style="margin-bottom: 0;">
                <i class="icon3-warning-sign text-error<?php echo !$note->isImportant() ? ' hide' : '' ?>" style="margin-right: 5px;"></i>

                <span><?php echo $note->subject ?></span>
            </h5>
            
            <small class="muted">
                Created <?php echo Yii::app()->getComponent('format')->formatDate($note->createdAt), $note->isDue() ? ', <strong>due on ' . Yii::app()->getComponent('format')->formatDate($note->dueAt) .'</strong>' : '' ?>
            </small>
            
            <p style="margin-top: 10px;">
                <?php echo $note->note ?>
            </p>
            
            <a class="btn btn-small btn-primary note-edit" data-id="<?php echo $note->id ?>">Edit note</a>

        <?php if (!$note->isArchived()): ?>
            <a class="btn btn-small btn-warning note-archive" href="<?php echo $this->createUrl('notes/archive', array('id' => $note->id, 'redirect' => $redirect)) ?>">Archive note</a>
        <?php else: ?>
            <a class="btn btn-small btn-warning note-archive" href="<?php echo $this->createUrl('notes/unarchive', array('id' => $note->id, 'redirect' => $redirect)) ?>">Unrchive note</a>
        <?php endif; ?>

        <a class="btn btn-small btn-info note-important<?php echo $note->isImportant() ? ' hide' : '' ?>" href="<?php echo $this->createUrl('notes/important', array('id' => $note->id, 'redirect' => $redirect)) ?>">Mark as Important</a>
        <a class="btn btn-small btn-inverse note-unimportant<?php echo !$note->isImportant() ? ' hide' : '' ?>" href="<?php echo $this->createUrl('notes/notimportant', array('id' => $note->id, 'redirect' => $redirect)) ?>">Mark as Unimportant</a>

        <?php if ($k < count($data->notes) - 1): ?>
            <hr />
        <?php endif; ?>
        </div>

        <div class="note-edit-form" data-id="<?php echo $note->id ?>" style="display: none;">
        <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'method' => 'post',
                'action' => array('notes/update', 'id' => $note->id, 'redirect' => $redirect),
            ));
            echo $form->textField($note, 'subject', array('class' => 'input-block-level'));
        ?>
            <small class="muted">
                Created <?php echo Yii::app()->getComponent('format')->formatDate($note->createdAt), $note->isDue() ? ', <strong>due on ' . Yii::app()->getComponent('format')->formatDate($note->dueAt) .'</strong>' : '' ?>
            </small>
        <?php
            echo $form->textArea($note, 'note', array('class' => 'input-block-level', 'placeholder' => 'type your note here...'));

            $note->dueAt = strtotime($note->dueAt) > 0 ? date('m/d/Y', strtotime($note->dueAt)) : '';
            echo $form->datePickerRow($note, 'dueAt', array('label' => 'Due At:', 'id' => 'Note_dueAt_' . $note->id));
        ?>
    
            <br />
            <input type="submit" class="btn btn-success btn-small" value="Save Note" />
            <a class="btn btn-inverse btn-small note-edit-cancel">Cancel</a>
            
        <?php $this->endWidget() ?>
        </div>
<?php endforeach; ?>
    </div>
    <div class="modal-footer">
        <a class="btn btn-inverse" data-dismiss="modal">Close</a>
    </div>

<?php
$this->endWidget();

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'add-note-' . $data->id));
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method' => 'post',
    'action' => array('notes/create', 'id' => $data->id, 'type' => $type, 'redirect' => $redirect),
));
?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
<?php if ($type == Note::TYPE_REVIEW): ?>
        <h4>Add Note: <em class="text-error"><?php echo $data->userName ?>'s</em> review of <em class="text-info"><?php echo $data->user->billingInfo->companyName ?></em></h4>
<?php else: ?>
        <h4>Add Note: <em class="text-info"><?php echo $data->billingInfo->companyName ?></em></h4>
<?php endif; ?>
    </div>
    <div class="modal-body">

<?php
$note = new Note;

echo CHtml::hiddenField('params', CJSON::Encode($_GET));

echo $form->label($note, 'subject', array('label' => 'Subject:'));
echo $form->textField($note, 'subject', array('class' => 'input-block-level'));

echo $form->label($note, 'note', array('label' => 'Note\'s text:'));
echo $form->textArea($note, 'note', array('class' => 'input-block-level', 'placeholder' => 'type your note here...'));

echo $form->datePickerRow($note, 'dueAt', array('label' => 'Due At:', 'id' => 'Note_dueAt_' . $data->id));?><br /><?php
echo $form->label($note, 'important', array('label' => 'Mark as important', 'style' => 'display: inline; vertical-align: top'));?>: <?php
echo $form->checkBox($note, 'important');
?>
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Add Note" />
        <a class="btn btn-inverse" data-dismiss="modal">Close</a>
    </div>

<?php
$this->endWidget();
$this->endWidget();
?>