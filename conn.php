<?php

class connec
{
    public $username = "root";
    public $password = "";
    public $server_name = "localhost";
    public $db_name = "arms_db";

    public $conn;

    function __construct()
    {
        $this->conn = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection Failed");
        }
        // else
        // {
        //     echo "connected";
        // }
    }

    function redirect($url)
    {
        header("Location: $url");
        die();
    }

    function select_all($table_name)
    {      
        $sql = "SELECT * FROM $table_name";
        $result=$this->conn->query($sql);
       
        
        return $result;
    }

    function select_login($table_name, $email)
    {
        $sql = "SELECT * FROM $table_name where email='$email'";
        $result = $this->conn->query($sql);
        return  $result;
    }

    function select_by_query($query)
    {
        $result=$this->conn->query($query);
        return $result;
    }
}   
?>
