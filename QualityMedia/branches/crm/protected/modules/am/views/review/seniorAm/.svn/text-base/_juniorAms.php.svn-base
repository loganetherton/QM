<li>
    <?php
        $srAm = $data->getSeniorManager();

        if($srAm) {
            $amName = sprintf('%s %s - %s %s', $data->firstName, $data->lastName, $srAm->firstName, $srAm->lastName);
        }
        else {
            $amName = sprintf('%s %s', $data->firstName, $data->lastName);
        }

        echo CHtml::link(
            $amName,
            $this->createUrl('/am/review/jr/'.$data->id),
            array('target'=>'_blank', 'style'=>'color: #6C3800')
        );
    ?>
</li>