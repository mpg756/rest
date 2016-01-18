<?php
/**
 * Created by PhpStorm.
 * User: valera
 * Date: 27.12.15
 * Time: 15:54
 */

namespace Api\Handler;


class FileWriter
{
    private $_file;
    private $_fileName = '';
    public function __construct($text, $_fileName = 'log.txt')
    {
        $this->_fileName = $_fileName;
        $this->openFile();
        $this->save($text);
        $this->closeFile();
    }
    private function save($string)
    {
        $fl = fwrite($this->_file,$string);
        if(!$fl) throw new \Exception('Error while writing into the file');
    }
    private function openFile()
    {
        if(file_exists($this->_fileName)) {
            $this->_file = fopen($this->_fileName,'a+');
        }
        else{
            $this->_file = fopen($this->_fileName,'w+');
        }
    }
    private function closeFile()
    {
        return fclose($this->_file);
    }
}