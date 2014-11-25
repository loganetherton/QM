<?php
    $htmlOptions = array(
        'name'=>'Review[action][private]',
        'class'=>'btn btn-color2 btn-small btn-commentAction',
        'rel'=>'#response-'.$data->id,
        'type'=>'submit',
    );
    if(!$data->isPrivateCommentAllowed()) {
        $htmlOptions['class'] .= ' disabled';
        $htmlOptions['disabled'] = 'disabled';
    }

    echo CHtml::htmlButton('<i class="icon3-comment"></i> Yelp Private Reply', $htmlOptions);
?>