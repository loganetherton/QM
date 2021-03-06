<?php
$icons = array('open', 'waiting', 'approved', 'nonapproved');
$reviewStatus = $this->currentTab == 'flagged' ? $data->flagApprovalStatus : $data->getApprovalStatus();
$statusIcon = $this->resourceUrl(sprintf('images/ico-%s.png', $icons[$reviewStatus]), 's3');
?>
<?php echo CHtml::tag('tr', array('id'=>'review-'.$data->id, 'class'=>$data->isProcessed() ? 'processed' : 'bold'), false, false); ?>
    <td class="approve-status" style="text-align: center;">
        <img width="32" src="<?php echo $statusIcon; ?>" alt="Approval Status" />
    </td>
    <td><?php echo CHtml::link(CHtml::encode($data->yelpBusiness->label), $data->yelpBusiness->getYelpProfileLink(), array('target'=>'_blank')); ?></td>
    <td>
        <?php
        echo $formatter->formatDate($data->reviewDate);
        echo $data->getIsFiltered() ? ' <span class="label pull-right label-important">Filtered</span>' : '';
        ?>
    </td>
    <td><?php echo $data->getLastActionTime(); ?></td>
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
                            <?php if($data->approvalStatus == Message::APPROVAL_STATUS_CHANGED): ?>
                                <span class='label label-info'>This response was changed by Senior Account Manager</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="clear"></div>

                <p class="line-base-thin"></p>

                <?php echo $formatter->formatHtml(nl2br($data->publicCommentContent)); ?>
            </div>

            <?php if($data->isWaitingForApproval()): ?>
                <?php echo CHtml::beginForm($this->createUrl('reviewAnswer/approve', array('id'=>$data->id))); ?>
                    <div class="row-fluid" style="display: none" id="editPublicReply-<?php echo $data->id; ?>">
                        <!-- Reply container -->
                        <div class="span12">
                            <textarea style="height: 100px" id="publicResponse-<?php echo $data->id; ?>" name="Review[answer]" class="input-block-level" rel="#replyError-<?php echo $data->id; ?>"><?php echo $data->publicCommentContent; ?></textarea>
                        </div>
                        <div class="span11" style="text-align: right">
                            <button type="submit" name="Review[action][publicWithChange]" class="btn btn-small btn-info">Approve</button>
                            <input type="button" class="btn btn-small btn-error btn-cancelPublicReplyEdit" rel="#editPublicReply-<?php echo $data->id; ?>" value="Cancel" />
                        </div>
                    </div>
                    <div class="row-fluid srActions">
                        <!-- Public comment -->
                        <div class="span12" id="editPublicReply-<?php echo $data->id; ?>-actions">
                            <!-- Response form -->
                                <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>
                                <?php $this->renderPartial('actions/publicCommentJr', array('data'=>$data)); ?>
                        </div>
                    </div>
                    <br />
                <?php echo CHtml::endForm(); ?>
            <?php endif; ?>
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

                <?php

                 if($message->isWaitingForApproval()): ?>
                    <?php echo CHtml::beginForm($this->createUrl('reviewAnswer/approve', array('id'=>$data->id))); ?>
                        <div class="row-fluid" style="display: none" id="editPrivateReply-<?php echo $data->id; ?>">
                            <!-- Reply container -->
                            <div class="span12">
                                <textarea style="height: 100px" id="privateResponse-<?php echo $data->id; ?>" name="Review[answer]" class="input-block-level" rel="#replyError-<?php echo $data->id; ?>"><?php echo $message->messageContent; ?></textarea>
                            </div>
                            <div class="span11" style="text-align: right">
                                <button type="submit" name="Review[action][privateWithChange]" class="btn btn-small btn-info">Approve</button>
                                <input type="button" class="btn btn-small btn-error btn-cancelPrivateReplyEdit" rel="#editPrivateReply-<?php echo $data->id; ?>" value="Cancel" />
                            </div>
                        </div>
                        <div class="row-fluid srActions">
                            <!-- Private reply -->
                            <div class="span12" id="editPrivateReply-<?php echo $data->id; ?>-actions">
                                <!-- Response form -->
                                    <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>
                                    <input type="hidden" name="Message[messageId]" value="<?php echo $message->id ?>" />
                                    <button style="margin-left: 30px" type="submit" name="Message[action][private]" class="btn btn-primary"> Approve &amp; Execute</button>

                                    <input type="button" name="Message[action][edit]" rel="#editPrivateReply-<?php echo $data->id; ?>" class="btn btn-warning btn-editPrivateReply pull-right" value="Edit Reply" />&nbsp;
                            </div>
                        </div>
                        <br />
                    <?php echo CHtml::endForm(); ?>
                <?php endif; ?>

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

            <?php if($data->isWaitingForFlagApproval()): ?>
                <?php echo CHtml::beginForm($this->createUrl('reviewAnswer/approve', array('id'=>$data->id))); ?>
                    <div class="row-fluid srActions">
                        <!-- Private reply -->
                        <div class="span12" id="editFlag-<?php echo $data->id; ?>-actions">
                            <!-- Response form -->
                                <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>
                                <?php $this->renderPartial('actions/flagJr', array('data'=>$data)); ?>
                        </div>
                    </div>
                    <br />
                <?php echo CHtml::endForm(); ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(!empty($data->seniorAmNote)): ?>
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

        <?php echo CHtml::beginForm($this->createUrl('reviewAnswer/approve', array('id'=>$data->id))); ?>
            <div class="row-fluid" style="display: none" id="seniorAmNote-<?php echo $data->id; ?>">
                <!-- Reply container -->
                <div class="span12">
                    <textarea style="height: 100px" id="seniorAmNote-<?php echo $data->id; ?>-textarea" name="Review[seniorAmNote]" class="input-block-level" rel="#replyError-<?php echo $data->id; ?>"><?php echo $data->seniorAmNote; ?></textarea>
                </div>
                <div class="span11" style="text-align: right">
                    <button type="submit" name="Review[action][seniorAmNote]" class="btn btn-small btn-info">Save note</button>
                    <input type="button" class="btn btn-small btn-error btn-cancelSeniorAmNote" rel="#seniorAmNote-<?php echo $data->id; ?>" value="Cancel" />
                </div>
            </div>
            <div class="row-fluid srActions">
                <!-- Senior Manager Note -->
                <div class="span12" id="seniorAmNote-<?php echo $data->id; ?>-actions">
                    <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>
                    <?php $this->renderPartial('actions/noteJr', array('data'=>$data)); ?>
                </div>
            </div>
        <?php echo CHtml::endForm(); ?>
    </td>
</tr>