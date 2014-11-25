<tr class="tb-child" id="client-notes-<?php echo $data->id ?>" style="display: none;">
    <td colspan="7">
        <?php
            $note = !empty($data->notes) ? $data->notes[0] : new Note;
            $this->renderPartial('/notes/form', array('data' => $note, 'am' => $data->id, 'redirect_client' => true, 'note_id' => $data->id));
         ?>
    </td>
</tr>