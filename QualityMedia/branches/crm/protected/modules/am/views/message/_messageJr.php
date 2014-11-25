<?php
$icons = array('open', 'waiting', 'approved', 'nonapproved');
$reviewStatus = $this->currentTab == 'flagged' ? $data->flagApprovalStatus : $data->getPmApprovalStatus();
$statusIcon = $this->resourceUrl(sprintf('images/ico-%s.png', $icons[$reviewStatus]), 's3');
?>
<?php echo CHtml::tag('tr', array('id'=>'message-'.$data->id, 'class'=>$data->isProcessed() ? 'processed' : 'bold'), false, false); ?>
    <td class="approve-status" style="text-align: center;">
        <img width="32" src="<?php echo $statusIcon; ?>" alt="Add Notes" />
    </td>
    <td>Yelp</td>
    <td><?php echo CHtml::link(CHtml::encode($data->yelpBusiness->label), $data->yelpBusiness->getYelpProfileLink(), array('target'=>'_blank')); ?></td>
    <td><?php echo $data->getLatestReceivedMessageDate(); ?></td>
    <td><?php echo $data->getLastActionTime(); ?></td>
    <td><?php echo CHtml::encode($data->userName); ?></td>
    <td><div class="rating" data-score="<?php echo $data->starRating; ?>"></div></td>
    <td class="expand">
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
                    </div>
                </div>

                <div class="clear"></div>

                <p class="line-base-thin"></p>

                <?php echo $formatter->formatHtml(nl2br($message->messageContent)); ?>
            </div>
        <?php endforeach; ?>

        <?php if($message->isWaitingForApproval()): ?>
            <?php echo CHtml::beginForm($this->createUrl('messageAnswer/approve', array('id'=>$message->id))); ?>
                <div class="row-fluid" style="display: none" id="editPrivateReply-<?php echo $message->id; ?>">
                    <!-- Reply container -->
                    <div class="span12">
                        <textarea style="height: 100px" id="privateResponse-<?php echo $message->id; ?>" name="Message[answer]" class="input-block-level" rel="#replyError-<?php echo $message->id; ?>"><?php echo $message->messageContent; ?></textarea>
                    </div>
                    <div class="span11" style="text-align: right">
                        <button type="submit" name="Message[action][privateWithChange]" class="btn btn-small btn-info">Approve</button>
                        <input type="button" class="btn btn-small btn-error btn-cancelPrivateReplyEdit" rel="#editPrivateReply-<?php echo $message->id; ?>" value="Cancel" />
                    </div>
                </div>
                <div class="row-fluid srActions">
                    <!-- Private reply -->
                    <div class="span12" id="editPrivateReply-<?php echo $message->id; ?>-actions">
                        <!-- Response form -->
                            <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>
                            <input type="hidden" name="Message[messageId]" value="<?php echo $message->id ?>" />
                            <button style="margin-left: 30px" type="submit" name="Message[action][private]" class="btn btn-primary"> Approve &amp; Execute</button>

                            <input type="button" name="Message[action][edit]" rel="#editPrivateReply-<?php echo $message->id; ?>" class="btn btn-warning btn-editPrivateReply pull-right" value="Edit Reply" />&nbsp;
                    </div>
                </div>
                <br />
            <?php echo CHtml::endForm(); ?>
        <?php endif; ?>

        <?php echo CHtml::beginForm($this->createUrl('messageAnswer/create', array('id'=>$data->id))); ?>
            <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>

            <?php echo CHtml::hiddenField('jrMode', 1); ?>

            <p class="author"><?php printf("%s %s's response:", Yii::app()->getUser()->getUser()->firstName, Yii::app()->getUser()->getUser()->lastName); ?><span style="display: inline-block; width: 40px">&nbsp;</span> <span id="replyError-<?php echo $data->id; ?>" style="display: none; font-weight: bold; color: #ff0000; margin-right: 50px">Your reply cannot be empty.</span></p>

            <textarea id="response-<?php echo $data->id; ?>" name="Message[answer]" class="input-block-level" rel="#replyError-<?php echo $data->id; ?>" placeholder="type your comment here"></textarea>

            <div class="row-fluid">
                <?php if($data->isPmArchived()): ?>
                    <div class="span12">
                        <?php
                            $htmlOptions = array('name'=>'Message[action][private]', 'class'=>'btn btn-primary btn-large btn-block btn-commentAction', 'rel'=>'#response-'.$data->id);
                            if(!$data->isPrivateCommentAllowed()) {
                                $htmlOptions['class'] .= ' disabled';
                                $htmlOptions['disabled'] = 'disabled';
                            }

                            echo CHtml::submitButton('Yelp Private Reply', $htmlOptions);
                        ?>
                    </div>
                <?php else: ?>
                    <div class="span6">
                        <?php
                            $htmlOptions = array('name'=>'Message[action][private]', 'class'=>'btn btn-primary btn-large btn-block btn-commentAction', 'rel'=>'#response-'.$data->id);
                            if(!$data->isPrivateCommentAllowed()) {
                                $htmlOptions['class'] .= ' disabled';
                                $htmlOptions['disabled'] = 'disabled';
                            }

                            echo CHtml::submitButton('Yelp Private Reply', $htmlOptions);
                        ?>
                    </div>

                    <div class="span6">
                        <input type="submit" name="Message[action][archive]" class="btn btn-warning btn-large btn-block" value="Archive Thread" />
                    </div>
                <?php endif; ?>
            </div>
        <?php echo CHtml::endForm(); ?>
    </td>
</tr>