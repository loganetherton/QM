<li>
    <?php echo CHtml::link(
            sprintf('%s %s', $data->firstName, $data->lastName),
            $this->createUrl('/am/message/jr/'.$data->id),
            array('target'=>'_blank', 'style'=>'color: #6C3800')
        );
    ?>
</li>