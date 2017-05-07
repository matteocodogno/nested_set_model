<?php

namespace NestedRoles\dal;

use mysqli;

class Connection {
	private $config;
	protected $conn;

	public function __construct() {
        $this->config = require __DIR__.'/../config.php';

        // TODO: check params
        // TODO: handle charset to return accent word

        $this->connect();
    }

    /**
     * 
     * 
     * @return boolean
     */
    public function isConnected() {
    	return isset($this->conn) && $this->conn->ping();
    }

    /**
     * connect to the mysql database
     */
    public function connect() {
    	if (!$this->isConnected()) {
    		$this->conn = new mysqli(
    		    $this->config['servername'],
                $this->config['username'],
                $this->config['password'],
                $this->config['dbname'],
                $this->config['port']);
//            $this->conn = new mysqli('127.0.0.1', 'nested', 'secret', 'nested', 6603);

            // die if SQL statement failed
			if ($this->conn->connect_error) {
			    die("Connection failed: " . $this->conn->connect_error);
			}
    	}
    }

    /**
     * [close description]
     */
    public function close() {
    	if ($this->isConnected()) {
    		$this->conn->close();
    	}
    }
}