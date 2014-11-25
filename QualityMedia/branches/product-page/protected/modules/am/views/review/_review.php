<?php echo CHtml::tag('tr', array('id'=>'review-'.$data->id, 'class'=>$data->isProcessed() ? 'processed' : 'bold'), false, false); ?>
    <td>Yelp</td>
    <td><?php echo CHtml::encode($data->user->billingInfo->companyName); ?></td>
    <td><?php echo $formatter->formatDate($data->reviewDate); ?></td>
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
    <td class="expand">
        <a href="<?php echo $this->createUrl('review/markAsRead', array('id'=>$data->id)); ?>">
            <span class="text">Expand</span> <i class="icon3-sort"></i>
        </a>
    </td>
</tr>

<tr class="tb-child">
    <td colspan="7">
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
                <div class="well well-small private_msg">
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

        <!-- Response form -->
        <?php echo CHtml::beginForm($this->createUrl('reviewAnswer/create', array('id'=>$data->id))); ?>
            <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>

            <p class="author"><?php printf('%s response:', Yii::app()->getUser()->getUser()->getFullName()); ?></p>

            <textarea name="Review[answer]" class="input-block-level" placeholder="type your comment here"></textarea>

            <div class="row-fluid">
                <!-- Public comment -->
                <div class="span3">
                    <?php $this->renderPartial('actions/publicComment', array('data'=>$data)); ?>
                </div>

                <!-- Private Message -->
                <div class="span3">
                    <?php $this->renderPartial('actions/privateMessage', array('data'=>$data)); ?>
                </div>

                <?php if($data->isOpened() || $data->isReplied()): ?>
                    <!-- Follow Up -->
                    <div class="span2">
                        <?php $this->renderPartial('actions/followUp'); ?>
                    </div>

                    <!-- Archive -->
                    <div class="span2">
                        <?php $this->renderPartial('actions/archive'); ?>
                    </div>

                    <!-- Flag -->
                    <div class="span2">
                        <?php $this->renderPartial('actions/flag'); ?>
                    </div>
                <?php endif; ?>

                <?php if($data->isFollowUp()): ?>
                    <!-- Archive -->
                    <div class="span3">
                        <?php $this->renderPartial('actions/archive'); ?>
                    </div>

                    <!-- Flag -->
                    <div class="span3">
                        <?php $this->renderPartial('actions/flag'); ?>
                    </div>
                <?php endif; ?>

                <?php if($data->isArchived()): ?>
                    <!-- Follow Up -->
                    <div class="span3">
                        <?php $this->renderPartial('actions/followUp'); ?>
                    </div>

                    <!-- Flag -->
                    <div class="span3">
                        <?php $this->renderPartial('actions/flag'); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php echo CHtml::endForm(); ?>
    </td>
</tr>