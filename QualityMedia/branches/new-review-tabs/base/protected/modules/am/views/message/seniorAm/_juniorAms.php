<li>
    <?php
        $srAm = $data->getSeniorManager();
        echo CHtml::link(
            sprintf('%s %s - %s %s', $data->firstName, $data->lastName, $srAm->firstName, $srAm->lastName),
            $this->createUrl('/am/message/jr/'.$data->id),
            array('target'=>'_blank', 'style'=>'color: #6C3800')
        );
    ?>
</li>