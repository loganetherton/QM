<button style="margin-left: 30px" type="submit" name="Review[action][public]" class="btn btn-primary"> Approve &amp; Execute</button>
<?php if(!$data->isRepliedPrivately()): ?>
    <button style="margin-left: 10px" type="submit" name="Review[action][setAsPrivate]" class="btn btn-info"> Set As Private</button>
<?php endif; ?>
<input type="button" name="Review[action][edit]" rel="#editPublicReply-<?php echo $data->id; ?>" class="btn btn-warning btn-editPublicReply pull-right" value="Edit Reply" />&nbsp;