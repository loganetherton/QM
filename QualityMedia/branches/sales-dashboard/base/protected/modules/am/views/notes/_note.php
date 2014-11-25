<tr class="<?php echo $data->isArchived() ? 'warning' : ($data->isDuePassed() ? 'error' : ($data->isImportant() ? 'success' : '')); ?>">
    <td>
        <span class="text">
        <?php
        if ($data->isReviewNote() && !empty($data->review)) {
            echo CHtml::encode($data->review->user->billingInfo->companyName);
        }
        elseif ($data->isClientNote() && !empty($data->user)) {
            echo CHtml::encode($data->user->billingInfo->companyName);
        }
        else {
            echo '<span style="font-style: italic;">N/A</span>';
        }
        ?>
        </span>
    </td>
    <?php if ($data->isReviewNote()): ?>
    <td>
        <span class="text">
        <?php
        if (!empty($data->review)) {
            $status = 'opened';
            if ($data->review->isReplied()) {
                $status = 'replied';
            }
            elseif ($data->review->isFollowUp()) {
                $status = 'followup';
            }
            elseif ($data->review->isFlagged()) {
                $status = 'flagged';
            }
            elseif ($data->review->isArchived()) {
                $status = 'archived';
            }

            echo CHtml::link($data->review->userName, array('review/' . $status . '?' . urlencode('AmReview[id]') . '=' . $data->reviewId));
        }
        else {
            echo '<span style="font-style: italic;">N/A</span>';
        }
    ?></span>
    </td>
    <?php endif; ?>
    <td>
        <span class="text">
            <?php echo strlen($data->subject) > 80 ? substr($data->subject, 0, 80) . '...' : $data->subject ?>
        </span>
    </td>
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
    <td colspan="<?php echo $data->type == Note::TYPE_REVIEW ? 6 : 5 ?>">
        <?php
        $this->renderPartial('form', array('data' => $data, 'type' => $data->isReviewNote() ? Note::TYPE_REVIEW : Note::TYPE_CLIENT));
        ?>
    </td>
</tr>