<input type="hidden" name="Review[messageId]" value="<?php echo $message->id ?>" />
<button style="margin-left: 30px" type="submit" name="Review[action][private]" class="btn btn-primary"> Approve &amp; Execute</button>
<?php if(!$data->isRepliedPublicly()): ?>
    <button style="margin-left: 10px" type="submit" name="Review[action][setAsPublic]" class="btn btn-info"> Set As Public</button>
<?php endif; ?>
<input type="button" name="Review[action][edit]" rel="#editPrivateReply-<?php echo $data->id; ?>" class="btn btn-warning btn-editPrivateReply pull-right" value="Edit Reply" />&nbsp;