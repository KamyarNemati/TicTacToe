<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

require_once dirname(__FILE__) . '/../utils/Utils.php';

class TicTacToe extends Utils {
    
    public function index_post() {
        $arr = [
            "stat"      =>      -1,
            "msg"       =>      "",
            "data"      =>      []
        ];
        
        $input_size = $this->_post_args["size"];
        $input_json = $this->_post_args["json"];
        $input_play = $this->_post_args["play"];
        
        $input_isInit = (isset($input_size) && !empty($input_size) && empty($input_json) && empty($input_play));
        $input_isPlay = (isset($input_json) && !empty($input_json) && isset($input_play) && !empty($input_play) && isset($input_size) && !empty($input_size));
        
        $this->output_json($obj);
    }
}