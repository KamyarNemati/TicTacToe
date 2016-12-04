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
            "stat" => -1,
            "msg" => "",
            "data" => []
        ];

        $input_size = $this->_post_args["size"];
        $input_json = $this->_post_args["json"];
        $input_play = $this->_post_args["play"];

        $input_isInit = (isset($input_size) && !empty($input_size) && empty($input_json) && empty($input_play));
        $input_isPlay = (isset($input_json) && !empty($input_json) && isset($input_play) && !empty($input_play) && isset($input_size) && !empty($input_size));

        $this->output_json($obj);
    }

}

class TicTacToeEngine {

    private $grid;              //The board
    private $n;                 //The board's size
    private $e;                 //Empty spot symbol
    private $p1;                //Player symbol
    private $p2;                //Kamyar (AI) symbol

    public function __construct($n = 3, $e = "?", $p1 = "X", $p2 = "O") {
        $this->grid = [];
        $this->n = $n;
        $this->e = $e;
        $this->p1 = $p1;
        $this->p2 = $p2;
    }

    public function setN($n) {
        $this->n = $n;
    }

    public function setGrid($grid) {
        if (count($grid) == $this->n) {
            $this->grid = $grid;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function initGrid() {
        $this->makeGrid($this->grid, $this->n, $this->e);
        return $this->grid;
    }

    public function play($move, &$arr = []) {
        $grid = $this->grid;
        $n = $this->n;
        $e = $this->e;
        $p1 = $this->p1;
        $p2 = $this->p2;
        //Player's move
        
        //-end-
        //Kamyar's move (AI)
        
        //-end-
        return $grid; // Return the updated board
    }
    
    private function makeGrid(&$grid, &$n, &$e) {
        for ($h = 0; $h < $n; ++$h) {
            for ($w = 0; $w < $n; ++$w) {
                $grid[$h][$w] = $e; // Place the empty symbol all over the board
            }
        }
    }

    
}
