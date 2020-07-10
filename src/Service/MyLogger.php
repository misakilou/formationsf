<?php

namespace App\Service;

class MyLogger
{
    private $path;

    public function __construct(string $logPath)
    {
        $this->path = $logPath;
    }

    public function write(string $str){

        $handle = fopen($this->path , 'a');
        $nowStr = date_create('now Europe/Paris')->format('Y/m/d H:i:s');
        fwrite($handle , $nowStr.':');
        fwrite($handle , $str.chr(10));
        fclose($handle);



    }


}