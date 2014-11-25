<tr id="message-<?php echo $data->id; ?>">
    <td>Yelp</td>
    <td><?php echo CHtml::encode($data->user->billingInfo->companyName); ?></td>
    <td><?php echo $data->getLatestMessageDate(); ?></td>
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

        <?php echo CHtml::beginForm($this->createUrl('messageAnswer/create', array('id'=>$data->id))); ?>
            <?php echo CHtml::hiddenField('params', CJSON::encode($_GET)); ?>

            <p class="author"><?php printf('%s response:', Yii::app()->getUser()->getUser()->getFullName()); ?></p>

            <textarea name="Message[answer]" class="input-block-level" placeholder="type your comment here"></textarea>

            <div class="row-fluid">
                <div class="span12">
                    <?php
                        $htmlOptions = array('name'=>'Message[action][private]', 'class'=>'btn btn-primary btn-large btn-block');
                        if(!$data->isPrivateCommentAllowed()) {
                            $htmlOptions['class'] .= ' disabled';
                            $htmlOptions['disabled'] = 'disabled';
                        }

                        echo CHtml::submitButton('Yelp Private Reply', $htmlOptions);
                    ?>
                </div>

                <?php if(0 && !$data->isPmArchived()): ?>
                    <div class="span6">
                        <input type="submit" name="Message[action][archive]" class="btn btn-warning btn-large btn-block" value="Archive & Do Nothing" />
                    </div>
                <?php endif; ?>
            </div>
        <?php echo CHtml::endForm(); ?>
    </td>
</tr>