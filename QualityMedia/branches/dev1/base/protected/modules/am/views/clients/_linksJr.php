<?php
    $chars      = array_merge(array('#'), range('A', 'Z'));
    $characters = $model->getValidFirstCharacters($accountManagerId);
?>

<div class="links">
    <span><?php echo CHtml::link('All', array('clients/jr', 'id' => $accountManagerId)); ?>&nbsp;|</span>

    <?php
        foreach($chars as $k => $char) {
            if($k > 0) {
                echo '<span>|&nbsp;';
            }
            if(in_array($char, $characters)) {
                echo CHtml::link($char, array('clients/jr', 'id' => $accountManagerId, 'Client'=>array('companyName'=>$char)));
            }
            else {
                echo $char;
            }

            echo '&nbsp;</span>';
        }
    ?>
</div>
