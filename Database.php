<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of conexion
 *
 * @author Nico
 */
class Database {

    private $_connection;
    private static $_instance; //The single instance
    private $_host = "localhost";
    private $_username = "octa_db";
    private $_password = 'UM%~$jnZ7}{O';
    private $_database = "octa_db";

    /*
      Get an instance of the Database
      @return Instance
     */

    public static function getInstance() {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct() {
        $this->_connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

        // Error handling
        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(), E_USER_ERROR);
        }
        $this->_connection->query("SET NAMES 'utf8'");
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() {
        
    }

    // Get mysqli connection
    public function getConnection() {
        return $this->_connection;
    }

}
