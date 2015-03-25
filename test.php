<?php

require_once 'init.php';

class Libro {
   
    public function verLibro(){
        echo get_called_class();
        echo get_parent_class();
        echo get_class();
        echo __CLASS__;
    }
}

class Test {
   
    private $_db;
    
    public function __construct() {
        $this->_db = Bdd::getInstance();
    }
    
    public function verUsuarios() {
        print_r($this->_db->fquery('test.sql' , array(2)));
    }
}

$n  = new Test();

$n->verUsuarios();