<?php
    $htmlOptions = array('name'=>'Review[action][private]', 'class'=>'btn btn-primary btn-large btn-block');
    if(!$data->isPrivateCommentAllowed()) {
        $htmlOptions['class'] .= ' disabled';
        $htmlOptions['disabled'] = 'disabled';
    }

    echo CHtml::submitButton('Yelp Private Reply', $htmlOptions);
?>