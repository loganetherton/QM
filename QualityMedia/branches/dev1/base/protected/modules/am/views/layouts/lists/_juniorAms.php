<li>
    <?php
        $srAm = $data->getSeniorManager();

        if($srAm) {
            $amName = sprintf('%s&nbsp;%s %s %s&nbsp;%s', $data->firstName, $data->lastName, CHtml::image($this->resourceUrl('images/ico-linked.png', 's3'), ''), $srAm->firstName, $srAm->lastName);
        }
        else {
            $amName = sprintf('%s %s', $data->firstName, $data->lastName);
        }

        echo CHtml::link(
            $amName,
            $this->createUrl($linkSection.$data->id),
            array('target'=>'_blank')
        );
    ?>
</li>