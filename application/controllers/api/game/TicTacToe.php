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
        
        $TTT = new TicTacToeEngine();
        $TTT->setN($input_size);

        if ($input_isInit) {
            $grid = $TTT->initGrid();
            $arr["data"] = json_encode($grid);
            $arr["stat"] = 0;
        } else {
            if ($input_isPlay) {
                $TTT->setGrid(json_decode($input_json));
                $arr["data"] = json_encode($TTT->play($input_play, $arr));
            } else {
                $arr["msg"] = "Wrong set of inputs.";
            }
        }

        $this->output_json($arr);
    }

}

class TicTacToeEngine {

    private $grid;
    private $n;
    private $e;
    private $p1;
    private $p2;
    
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
        $this->translateIndex($grid, $n, $move, TRUE, $p1); // Make the move (player)
        if (!$this->anyone($grid, $n, $e, $p1, $p2, $arr)) { // To check if there's any winner, if not, then
            //Kamyar's move (AI)
            $x = $this->aiMove($grid, $n, $e); // Pick the best move
            $this->translateIndex($grid, $n, $x, TRUE, $p2); // Make the move
            $this->anyone($grid, $n, $e, $p1, $p2, $arr); // Did AI win? maybe not (yet) :)
            //-end-
        }
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

    private function anyone(&$grid, &$n, &$e, &$p1, &$p2, &$arr) {
        $r = $this->check($grid, $n, $e); // Any winner?
        if ($r == $p1 || $r == $p2) {
            $arr["msg"] = ($r == $p1 ? "Player Wins" : ($r == $p2 ? "Kamyar Wins" : "Undefined outcome :|"));
            $arr["stat"] = 1;
            return TRUE; // Yes there is
        } else {
            $arr["stat"] = 0;
        }
        if ($this->isOver($grid, $n, $e)) { // Game over?
            $arr["msg"] = "Tie";
            $arr["stat"] = 1;
            return TRUE; // Yes it is
        }
        return FALSE;
    }

    private function check(&$grid, &$n, &$e) {
        //Check diagonal-traversal (backslash)
        $c = $e;
        $s = 0;
        $x = 1;
        for ($i = 1; $i <= $n; ++$i) {
            $k = $this->translateIndex($grid, $n, $x);
            $x += ($n + 1);
            if ($k == $e) {
                continue;
            } else {
                if ($c == $e) {
                    $c = $k;
                    ++$s;
                } else {
                    if ($c != $k) {
                        break;
                    } else {
                        ++$s;
                        if ($s == $n) {
                            return $c;
                        }
                    }
                }
            }
        }
        //-end-
        //Check diagonal-traversal (slash)
        $c = $e;
        $s = 0;
        $x = 1;
        for ($i = 1; $i <= $n; ++$i) {
            $x += ($n - 1);
            $k = $this->translateIndex($grid, $n, $x);
            if ($k == $e) {
                continue;
            } else {
                if ($c == $e) {
                    $c = $k;
                    ++$s;
                } else {
                    if ($c != $k) {
                        break;
                    } else {
                        ++$s;
                        if ($s == $n) {
                            return $c;
                        }
                    }
                }
            }
        }
        //-end-
        //Check horizontal-traversal (all levels)
        for ($h = 0; $h < $n; ++$h) {
            $c = $e;
            $s = 0;
            for ($w = 0; $w < $n; ++$w) {
                $k = $grid[$h][$w];
                if ($k == $e) {
                    continue;
                } else {
                    if ($c == $e) {
                        $c = $k;
                        ++$s;
                    } else {
                        if ($c != $k) {
                            break;
                        } else {
                            ++$s;
                            if ($s == $n) {
                                return $c;
                            }
                        }
                    }
                }
            }
        }
        //-end-
        //Check vertical-traversal (all levels)
        for ($h = 0; $h < $n; ++$h) {
            $c = $e;
            $s = 0;
            for ($w = 0; $w < $n; ++$w) {
                $k = $grid[$w][$h];
                if ($k == $e) {
                    continue;
                } else {
                    if ($c == $e) {
                        $c = $k;
                        ++$s;
                    } else {
                        if ($c != $k) {
                            break;
                        } else {
                            ++$s;
                            if ($s == $n) {
                                return $c;
                            }
                        }
                    }
                }
            }
        }
        //-end-
        return $e;
    }

    private function isOver(&$grid, &$n, &$e) {
        $m = $n * $n;
        for ($i = 1; $i <= $m; ++$i) {
            if ($this->translateIndex($grid, $n, $i) == $e) {
                return FALSE;
            }
        }
        return TRUE;
    }

    private function aiMove($grid, &$n, &$e) {
        $m = $n * $n;
        for($i = 1; $i <= $m; ++$i) {
            if($this->translateIndex($grid, $n, $i) == $e) {
                return $i;
            }
        }
    }

    private function translateIndex(&$grid, &$n, $idx, $set = FALSE, $put = "") {
        $h = (is_int($idx / $n) ? floor($idx / $n) - 1 : floor($idx / $n));
        $w = ($idx % $n == 0 ? $n - 1 : ($idx % $n) - 1);
        if ($set) {
            $grid[$h][$w] = $put;
        }
        return $grid[$h][$w];
    }
}