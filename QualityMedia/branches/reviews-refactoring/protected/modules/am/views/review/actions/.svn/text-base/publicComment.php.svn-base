<?php
    $htmlOptions = array('name'=>'Review[action][public]', 'class'=>'btn btn-info btn-large btn-block');
    if($data->hasPublicComment()) {
        $htmlOptions['class'] .= ' disabled';
        $htmlOptions['disabled'] = 'disabled';
    }

    echo CHtml::submitButton('Yelp Public Reply', $htmlOptions);
?>