<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of checker
 *
 * @author Vladdimir
 */
class Checker {
    var $content = '';
    var $branches = array();
    var $robot_ok;

    //class consructor
    public function __construct($url) {
        $url = preg_replace('#^http://#is', '', trim($url));
        $url = current(explode('/', $url));
        if(@file_get_contents('http://' . $url . '/robots.txt')){
            $this->robot_ok = 'ОК';
        }  else {
            echo 'Проверка наличия файла robots.txt: ОШИБКА';
            exit;
        }
        $this->content = trim(file_get_contents('http://' . $url . '/robots.txt'));
        $s = preg_split('#[\n]+#is', $this->content);
        
        $current_user_agent = '';
        foreach($s as $line)
        {
            $line = trim(current(explode('#', trim($line), 2)));
            if (substr_count($line, ':')<1) continue;
            $line = explode(':', $line, 2);
            $current_directive = strtolower(trim($line[0]));
            $current_value = trim($line[1]);
            if ($current_directive == 'user-agent') 
            {
                $current_user_agent = $current_value;
            }
            elseif($current_user_agent!='')
            {
                $this->branches[$current_user_agent][$current_directive][] = $current_value;
            }
        }
    }
    
    //Check robots.txt
    public function get_robots() {
        if(isset($this->content)){
            return 'ОК';
        }else{
            return 'ОШИБКА';
            exit();
        }
    }
    
    //Check directives
    public function get_directive($directive){
        
       $directive = strtolower($directive);
       $ret = 0;
       
        foreach($this->branches as $key=>$value){
            if(isset($value[$directive])){
                $ret++;
            }
        }
        if(($ret) > 0){
            return 'OK';
        }else{
            return 'ОШИБКА';
        }
    }
    
    //Check directives in robots.txt
    public function count_directive($directive){
        
       $directive = strtolower($directive);
       $ret = 0;
       
        foreach($this->branches as $key=>$value){
            if(isset($value[$directive])){
                $ret++;
            }
        }
        if(($ret) == 1){
            return 'OK';
        }else{
            return 'ОШИБКА';
        }
    }
    
    //Check size of robots.txt
    public function get_size($url){
        $url = 'http://'.$url.'/robots.txt';
        $parse = parse_url($url);
        $host = $parse['host'];
        $fp = @fsockopen ($host, 80, $errno, $errstr, 20);
        if(!$fp){
          $ret = 0;
        }else{
          $host = $parse['host'];
          fputs($fp, "HEAD ".$url." HTTP/1.1\r\n");
          fputs($fp, "HOST: ".$host."\r\n");
          fputs($fp, "Connection: close\r\n\r\n");
          $headers = "";
          while (!feof($fp)){
            $headers .= fgets ($fp, 128);
          }
          fclose ($fp);
          $headers = strtolower($headers);
          $array = preg_split("|[\s,]+|",$headers);
          $key = array_search('content-length:',$array);
          $ret = $array[$key+1];
        }
        if($array[1]==200) return $ret;
        $filesize = -1*$array[1];

        if($filesize < 32768){
            return 'OK';
        }else{
            return 'ОШИБКА';
        }
    }
    
    public function get_response($url) {
        $url ='http://'.$url.'/robots.txt';
        $Headers = @get_headers($url);
        if(preg_match('/200/', implode(',', $Headers))){
            return "ОК";
        } else {
            return "ОШИБКА";
        }
    }
}
