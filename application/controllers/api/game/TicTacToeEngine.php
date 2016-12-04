<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 * @name TicTacToeEngine
 * @package TicTacToeEngine
 * @discription This class accepts an arbitrary state of the game and makes a move based on the AI's decision. The size of the board has to be given on every initialisation.
 */
class TicTacToeEngine {
    /*
     * Definition of players
     */

    const PLAYER1 = 1;
    const PLAYER2 = 2; //The AI (He's name is Kamyar)

    private $move1 = -1;        //Best move for player
    private $move2 = -1;        //Best move for kamyar (the AI)
    private $grid;              //The board                     (Array)
    private $n;                 //The board's size              (Int)
    private $e;                 //Empty spot symbol             (String)
    private $p1;                //Player symbol                 (String)
    private $p2;                //Kamyar (AI) symbol            (String)

    /**
     * 
     * @param int $n
     * @param char $e
     * @param char $p1
     * @param char $p2
     * @author Kamyar
     */

    public function __construct($n = 3, $e = "?", $p1 = "X", $p2 = "O") {
        /* Attributes initialisation  */
        $this->grid = [];
        $this->n = $n;
        $this->e = $e;
        $this->p1 = $p1;
        $this->p2 = $p2;
    }

    /**
     * 
     * @param int $n
     * @author Kamyar
     * @discription Size 3 is recommended. However, size 4 and more requires significant amount of computation power in order for the AI to make its decision.
     */
    public function setN($n) {
        $this->n = $n;
    }

    /**
     * 
     * @param array $grid
     * @return boolean If board's size does not match the size of the game state
     * @author Kamyar
     */
    public function setGrid($grid) {
        if (count($grid) == $this->n) { //The size of the given array (game state) must be equal to the given board's size
            $this->grid = $grid;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @return 2-dimentional array
     * @author Kamyar
     */
    public function initGrid() {
        $this->makeGrid($this->grid, $this->n, $this->e);
        return $this->grid;
    }

    /**
     * 
     * @param int $move
     * @param accosiative-array $arr (To return any error messages)
     * @return 2-dimentional array (The new state of the game)
     * @author Kamyar
     */
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
            $this->aiMove($grid, $n, $e, $p1, $p2); // Pick the best move (store it in the class variable: 'move2')
            $this->translateIndex($grid, $n, $this->move2, TRUE, $p2); // Make the move
            $this->anyone($grid, $n, $e, $p1, $p2, $arr); // Did AI win? maybe not (yet) :)
            //-end-
        }
        //-end-
        /*
         * Making a move is basically translating a move into a 2-dimentional array index.
         * To make a move requires a number on the board.
         */
        return $grid; // Return the updated board
    }

    /**
     * 
     * @param 2-dimentional array $grid
     * @param int $n
     * @param char $e
     * @author Kamyar
     * @discription Initialisation of a 2-dimentional array
     */
    private function makeGrid(&$grid, &$n, &$e) {
        for ($h = 0; $h < $n; ++$h) {
            for ($w = 0; $w < $n; ++$w) {
                $grid[$h][$w] = $e; // Place the empty symbol all over the board
            }
        }
    }

    /**
     * 
     * @param 2-dimentional array $grid
     * @param int $n
     * @param char $e
     * @param char $p1
     * @param char $p2
     * @param accosiative-array $arr $arr
     * @return boolean
     * @author Kamyar
     */
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

    /**
     * 
     * @param 2-dimentional array $grid
     * @param int $n
     * @param char $e
     * @return char
     * @author Kamyar
     */
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

    /**
     * 
     * @param 2-dimentional array $grid
     * @param int $n
     * @param char $e
     * @return boolean
     * @author Kamyar
     */
    private function isOver(&$grid, &$n, &$e) {
        $m = $n * $n;
        for ($i = 1; $i <= $m; ++$i) {
            if ($this->translateIndex($grid, $n, $i) == $e) { // The game isn't over yet if there's any empty symbol in the board
                return FALSE;
            }
        }
        return TRUE; // Otherwise, it is over
    }

    /**
     * 
     * @algorithm The <b>MiniMax</b> algorithm to maximise the AI chance of winning.
     * 
     * @param 2-dimentional array $grid
     * @param int $n
     * @param char $e
     * @param char $p1
     * @param char $p2
     * @param int $depth
     * @param ENUM $player
     * @return int
     * @author Kamyar
     */
    private function aiMove($grid, &$n, &$e, &$p1, &$p2, $depth = 0, $player = TicTacToeEngine::PLAYER2) {
        $min = INF;
        $max = -INF;

        $r = $this->check($grid, $n, $e); // Get the current state of the board

        if ($r == $p1) { // Player wins
            return 1;
        } else {
            if ($r == $p2) { // AI wins
                return -1;
            } else {
                if ($this->isOver($grid, $n, $e)) { // Game over :-
                    return 0;
                }
            }
        }

        $m = $n * $n; // Board's size

        for ($i = 1; $i <= $m; ++$i) { // Go through...
            $k = $this->translateIndex($grid, $n, $i); // Get the symbol at the current spot
            if ($k == $e) { // The algorithm is only interested in empty spots
                if ($player == TicTacToeEngine::PLAYER2) { // Who's turn is it? AI.
                    $this->translateIndex($grid, $n, $i, TRUE, $p2); // Make a move then...
                    $val = $this->aiMove($grid, $n, $e, $p1, $p2, $depth + 1, TicTacToeEngine::PLAYER1); // The same thing all over again :(
                    $this->translateIndex($grid, $n, $i, TRUE, $e); // Revert back the move
                    if ($val < $min) {
                        $min = $val;
                        if ($depth == 0) {
                            $this->move2 = $i; // Save/overwrite the best move for AI
                        }
                    }
                } else {
                    if ($player == TicTacToeEngine::PLAYER1) { // Who's turn is it? the Player
                        $this->translateIndex($grid, $n, $i, TRUE, $p1);
                        $val = $this->aiMove($grid, $n, $e, $p1, $p2, $depth + 1, TicTacToeEngine::PLAYER2);
                        $this->translateIndex($grid, $n, $i, TRUE, $e);
                        if ($val > $max) {
                            $max = $val;
                            if ($depth == 0) {
                                $this->move1 = $i; // Save/overwrite the best move for the player
                            }
                        }
                    }
                }
            }
        }

        if ($player == TicTacToeEngine::PLAYER1) {
            return $max; // Return the value of the move for the player
        } else {
            if ($player == TicTacToeEngine::PLAYER2) {
                return $min; // Return the value of the move for AI
            }
        }
    }

    /**
     * 
     * @param 2-dimentional array $grid
     * @param int $n
     * @param int $idx (The move)
     * @param boolean $set
     * @param char $put
     * @return type
     * @author Kamyar
     */
    private function translateIndex(&$grid, &$n, $idx, $set = FALSE, $put = "") { // Here's my favorite function :)
        $h = (is_int($idx / $n) ? floor($idx / $n) - 1 : floor($idx / $n));
        $w = ($idx % $n == 0 ? $n - 1 : ($idx % $n) - 1);
        if ($set) { // Update the symbol if it's set
            $grid[$h][$w] = $put;
        } //Otherwise,
        return $grid[$h][$w]; // Return the symbol anyway
    }

    /*
     * Dumb AI
     *  $m = $n * $n;
      for($i = 1; $i <= $m; ++$i) {
      if($this->translateIndex($grid, $n, $i) == $e) {
      return $i;
      }
      }
     */
}
