<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

class TicTacToe extends CI_Controller {
    
    /**
     * @author Kamyar
     */
    public function index() {
        $this->load->view("game/tic_tac_toe");
    }
}