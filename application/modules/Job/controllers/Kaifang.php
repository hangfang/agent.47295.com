<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * 导入开放数据
 */
class KaifangController extends BasicController{
    public function importAction(){
        $file = $this->_request->getParam('file', '0,1,2,3,4,5,6,7,8,9,10');
        $file = explode(',', $file);
        
        foreach($file as $_file){
            $_file = 'C:/Users/ever10/Desktop/sql/2000/'.$_file.'.csv';
            if(!file_exists($_file)){
                echo 'file not exist...file:'.$_file."\n";
                continue;
            }
            
            $_file = fopen($_file,"r");
            $header = explode(',', trim(fgets($_file), '﻿'));
            $headerLength = count($header);
            $_lineNumber = 1;
            while(! feof($_file)){
                $_line = fgets($_file);
                $_value = explode(',', $_line);
                if(!is_array($_value) || count($_value)!==$headerLength){
                    'line: '.$_lineNumber.' data invalid.'.$_line."\n";
                    continue;
                }
                
                $_replace = array_combine($header, $_value);
                if(!Agent_KaifangModel::replace($_replace)){
                    echo '插入数据失败';
                }
            }

            fclose($_file);
        }
    }
}
