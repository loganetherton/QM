<?php
    $htmlOptions = array(
        'name'=>'Review[action][public]',
        'class'=>'btn btn-color1 btn-small btn-commentAction',
        'rel'=>'#response-'.$data->id,
        'type'=>'submit',
    );
    if($data->hasPublicComment()) {
        $htmlOptions['class'] .= ' disabled';
        $htmlOptions['disabled'] = 'disabled';
    }

    echo CHtml::htmlButton('<i class="icon3-bullhorn"></i> Yelp Public Reply', $htmlOptions);
?>