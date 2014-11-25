<?php
$icons = array('open', 'waiting', 'approved', 'nonapproved');
$reviewStatus = $this->currentTab == 'flagged' ? $data->flagApprovalStatus : $data->getApprovalStatus();
$statusIcon = $this->resourceUrl(sprintf('images/ico-%s.png', $icons[$reviewStatus]), 's3');
?>
<?php echo CHtml::tag('tr', array('id'=>'review-'.$data->id, 'class'=>$data->isProcessed() ? 'processed' : 'bold'), false, false);

    //Add approval status icons if the user is Jr AM
    if(!Yii::app()->getUser()->isSenior()):
     ?>
        <td class="approve-status" style="text-align: center;">
            <img width="32" src="<?php echo $statusIcon; ?>" alt="Add Notes" />
        </td>
    <?php endif; ?>
    <td class="add-note" style="text-align: center;">
        <a href="#" data-id="<?php echo $data->id ?>">
            <img src="<?php echo $this->resourceUrl('images/note.png', 's3'); ?>" alt="Icon" />
        </a>
    </td>
    <td><?php echo CHtml::link(CHtml::encode($data->yelpBusiness->label), $data->yelpBusiness->getYelpProfileLink(), array('target'=>'_blank')); ?></td>
    <td>
        <?php
        echo $formatter->formatDate($data->reviewDate);
        echo $data->getIsFiltered() ? ' <span class="label pull-right label-important">Filtered</span>' : '';
        ?>
    </td>
    <td class="last-action"><?php echo $data->getLastActionTime(); ?></td>
    <td>
        <div class="userInfo" display="block" data-content="<?php echo $data->getIsEliteUser() ? '<span class=\'label label-important\'>Elite</span><br />' : '' ?>Friends: <?php echo $data->userFriendCount; ?><br />Reviews: <?php echo $data->userReviewCount; ?>">
            <?php
                echo CHtml::encode($data->userName);
                echo $data->getIsEliteUser() ? ' <span class="label pull-right label-important">Elite</span>' : '';
            ?>
        </div>
    </td>
    <td><div class="rating" data-score="<?php echo $data->starRating; ?>"></div></td>
    <td class="expand show-review">
        <a href="<?php echo $this->createUrl('review/markAsRead', array('id'=>$data->id)); ?>">
            <span class="text">Expand</span> <i class="icon3-sort"></i>
        </a>
    </td>
</tr>

<tr class="tb-child">
    <td colspan="8">
        <div class="well well-small">
            <?php echo $formatter->formatHtml($data->reviewContent); ?>
        </div>

        <?php if($data->hasUpdates()): ?>
            <?php foreach($data->updates as $update): ?>
                <div class="well well-small update_msg">
                    <div class="more-info clearfix">
                        <div class="info_left">
                            <div class="owner">
                                <a href="#">Update</a>
                            </div>
                            <div class="other_info">
                                <span class="date"><?php echo $formatter->formatDate($update->updateDate); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>

                    <p class="line-base-thin"></p>

                    <?php echo $formatter->formatHtml(nl2br($update->updateContent)); ?>
                </div>
            <?php endforeach; ?>

            <?php if($data->hasPublicComment() || $data->hasPrivateMessages() || $data->isFlagged()): ?>
                 <p class="line-base"></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($data->hasPublicComment()): ?>
            <div class="well well-small public_msg">
                <div class="more-info clearfix">
                    <div class="info_left">
                        <div class="owner">
                            <a href="#"><?php echo CHtml::encode($data->publicCommentAuthor); ?></a>
                        </div>
                        <div class="other_info">
                            <span class="private1">Public Comment</span>
                            &nbsp;
                            <span class="date"><?php echo $formatter->formatDate($data->publicCommentDate); ?></span>

                            <?php if($data->approvalStatus == Review::APPROVAL_STATUS_CHANGED): ?>
                                <span class='label label-info'>This response was changed by Senior Account Manager</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="clear"></div>

                <p class="line-base-thin"></p>

                <?php echo $formatter->formatHtml(nl2br($data->publicCommentContent)); ?>
            </div>

            <?php if($data->hasPrivateMessages() || $data->isFlagged()): ?>
                 <p class="line-base"></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($data->hasPrivateMessages()): ?>
            <?php foreach($data->privateMessages as $message): ?>
                <?php if($message->isComment()) { continue; } ?>

                <div class="well well-small private_msg" data-id="<?php echo $message->id; ?>">
                    <div class="more-info clearfix">
                        <div class="info_left">
                            <div class="owner">
                                <a href="#"><?php echo CHtml::encode($message->from); ?></a>
                            </div>
                            <div class="other_info">
                                <span class="private1">Private Comment</span>
                                &nbsp;
                                <span class="date"><?php echo $message->getMessageDate(); ?></span>
                            </div>

                            <?php if($message->isBizOwnerAnswer()): ?>
                                <div style="float:right;margin-left:20px;"><span class="label label-info">Business owner answer</span></div>
                            <?php endif; ?>

                            <?php if($message->isStaffAnswer()): ?>
                                <div style="float:right;margin-left:20px;"><span class="label label-warning">Account Manager answer</span></div>
                            <?php endif; ?>

                            <?php if($message->approvalStatus == Message::APPROVAL_STATUS_CHANGED): ?>
                                <span class='label label-info'>This response was changed by Senior Account Manager</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="clear"></div>

                    <p class="line-base-thin"></p>

                    <?php echo $formatter->formatHtml(nl2br($message->messageContent)); ?>
                </div>
            <?php endforeach; ?>

            <?php if($data->isFlagged()): ?>
                 <p class="line-base"></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($data->isFlagged()): ?>
           <div class="well well-small flagged_msg">
                <div class="more-info clearfix">
                    <div class="info_left">
                        <div class="owner">
                            <a href="#">Flagged At:</a>
                            <?php echo $formatter->formatDatetime($data->flaggedAt); ?>
                        </div>
                    </div>
                </div>

                <div class="clear"></div>

                <p class="line-base-thin"></p>

                <?php echo $formatter->formatHtml(nl2br($data->flagReason)); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($data->seniorAmNote)): ?>
            <p class="line-base-thin"></p>

           <div class="well well-small flagged_msg">
                <div class="more-info clearfix">
                    <div class="info_left">
                        <div class="owner">
                            <a href="#"><?php printf("%s %s's note:", Yii::app()->getUser()->getUser()->firstName, Yii::app()->getUser()->getUser()->lastName); ?></a>
                            <?php echo $formatter->formatDatetime($data->seniorAmNoteUpdateDate); ?>
                        </div>
                    </div>
                </div>

                <div class="clear"></div>

                <p class="line-base-thin"></p>

                <?php echo $formatter->formatHtml(nl2br($data->seniorAmNote)); ?>
            </div>
        <?php endif; ?>

        <!-- Response form -->
        <?php echo CHtml::beginForm($this->createUrl('reviewAnswer/create', array('id'=>$data->id))); ?>
            <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>

            <p class="author">
                <div class="author span8 pull-left">
                    <?php printf("%s %s's response:", Yii::app()->getUser()->getUser()->firstName, Yii::app()->getUser()->getUser()->lastName); ?>
                </div>
                <div class="span3 pull-right" style="text-align: right">
                    <button class="notes-view btn btn-color6 btn-small<?php echo empty($data->notes) ? ' disabled' : '' ?>" data-id="<?php echo $data->id ?>"<?php echo empty($data->notes) ? ' disabled' : '' ?>>
                        <img src="<?php echo $this->resourceUrl('images/note-bw' . (empty($data->notes) ? '-dis' : '') . '.png', 's3') ?>" /> View Notes
                    </button>
                </div>
                <span style="display: inline-block; width: 40px">&nbsp;</span>
                <span id="replyError-<?php echo $data->id; ?>" style="display: none; font-weight: bold; color: #ff0000; margin-right: 50px">Your reply cannot be empty.</span>
            </p>
            <?php if($data->replyBlocked): ?>
            <p>
                <span class="label">This user has blocked private replies</span>
            </p>
            <?php endif; ?>

            <textarea id="response-<?php echo $data->id; ?>" name="Review[answer]" class="input-block-level" rel="#replyError-<?php echo $data->id; ?>" placeholder="type your comment here"></textarea>

            <div class="row-fluid">
                <!-- Public comment -->
                <div class="span6">
                    <?php $this->renderPartial('actions/publicComment', array('data'=>$data)); ?>

                    <?php if(!$data->replyBlocked): ?>
                <!-- Private Message -->
                    <?php $this->renderPartial('actions/privateMessage', array('data'=>$data)); ?>
                    <?php endif; ?>
                </div>
                <div class="span6 pull-right">

                <?php if($data->isOpened() || $data->isReplied()): ?>
                    <!-- Follow Up -->
                        <?php $this->renderPartial('actions/followUp'); ?>

                    <!-- Archive -->
                        <?php $this->renderPartial('actions/archive'); ?>

                    <!-- Flag -->
                        <?php $this->renderPartial('actions/flag'); ?>
                <?php endif; ?>

                <?php if($data->isFollowUp()): ?>
                    <!-- Archive -->
                        <?php $this->renderPartial('actions/archive'); ?>

                    <!-- Flag -->
                        <?php $this->renderPartial('actions/flag'); ?>
                <?php endif; ?>

                <?php if($data->isArchived()): ?>
                    <!-- Follow Up -->
                        <?php $this->renderPartial('actions/followUp'); ?>

                    <!-- Flag -->
                        <?php $this->renderPartial('actions/flag'); ?>
                <?php endif; ?>
                </div>
            </div>
        <?php echo CHtml::endForm(); ?>
    </td>
</tr>