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
        
        //...
        
        $this->output_json($obj);
    }
}