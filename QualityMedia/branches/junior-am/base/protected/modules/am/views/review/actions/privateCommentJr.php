<input type="hidden" name="Review[messageId]" value="<?php echo $message->id ?>" />
<button style="margin-left: 30px" type="submit" name="Review[action][private]" class="btn btn-primary"> Approve &amp; Execute</button>

<input type="button" name="Review[action][edit]" rel="#editPrivateReply-<?php echo $data->id; ?>" class="btn btn-warning btn-editPrivateReply pull-right" value="Edit Reply" />&nbsp;