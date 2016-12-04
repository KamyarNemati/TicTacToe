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
require_once dirname(__FILE__) . '/TicTacToeEngine.php';

class TicTacToe extends Utils {

    public function index_post() {
        $arr = [
            "stat" => -1,
            "msg" => "",
            "data" => []
        ];

        $input_size = $this->_post_args["size"]; //Size of the board
        $input_json = $this->_post_args["json"]; //Game's state (in JSON form)
        $input_play = $this->_post_args["play"]; //The move (1 <= move <= size*size)

        $input_isInit = (isset($input_size) && !empty($input_size) && empty($input_json) && empty($input_play));
        $input_isPlay = (isset($input_json) && !empty($input_json) && isset($input_play) && !empty($input_play) && isset($input_size) && !empty($input_size));

        //instantiate the game object
        $TTT = new TicTacToeEngine();
        $TTT->setN($input_size); //Set the board's size

        if ($input_isInit) { //If no game's state is given, then initialise one
            $grid = $TTT->initGrid();
            $arr["data"] = json_encode($grid); //Return the initial state
            $arr["stat"] = 0;
        } else {
            if ($input_isPlay) { //Let's play...
                $TTT->setGrid(json_decode($input_json)); //Transform the JSON into array and give it to the game engine
                $arr["data"] = json_encode($TTT->play($input_play, $arr)); //Let AI make a move and the return back the new game state
            } else {
                $arr["msg"] = "Wrong set of inputs.";
            }
        }

        $this->output_json($arr);
    }

}
