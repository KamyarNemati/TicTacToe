<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

require_once 'application/libraries/REST_Controller.php';

class Utils extends REST_Controller {

    /**
     * 
     * @param type $object
     * @author Kamyar
     */
    public function output_json($obj) {
        $this->output
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}