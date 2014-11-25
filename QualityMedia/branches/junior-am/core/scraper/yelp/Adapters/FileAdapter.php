<?php

require __DIR__ . '/AdapterInterface.php';

class FileAdapter implements AdapterInterface
{
    function __construct($filename = null)
    {
        if (!$filename) {
            $filename =  '/tmp/' . uniqid() . '.txt';
        }

        $this->fp = fopen($filename, 'a+');
    }

    public function save($array)
    {
        fputs($this->fp, serialize($array) . "\n");
    }
}
