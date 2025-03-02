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

    function select($table_name,$id)
    {      
        $sql = "SELECT * FROM $table_name where id=$id";
        $result=$this->conn->query($sql);
        return  $result;
    }

    function insert($query,$msg)
    { 
        if($this->conn->query($query)===TRUE)
        {
             echo '<script> alert("'.$msg.'");</script>' ;
                //echo "inserted";
        }
        else
        {
             echo '<script> alert("'.$this->conn->error.'");</script>' ;
               // echo $this->conn->error;
        }
    }

    function delete($table_name,$id)
    { 
        $query="DELETE FROM $table_name WHERE id=$id";
        $this->conn->query($query);
    }

    function delete_order($table, $order)
    { 
        $query="DELETE FROM $table WHERE order_id=$order";
        if($this->conn->query($query)===TRUE)
        {
             echo '<script> alert("Data Removed Successfully");</script>' ;
        }
        else
        {
             echo '<script> alert("'.$this->conn->error.'");</script>' ;
        }
    }

    function delete_reserve($reserve)
    { 
        $query="DELETE FROM res WHERE reserve_id=$reserve";
        if($this->conn->query($query)===TRUE)
        {
             echo '<script> alert("Data Removed Successfully");</script>' ;
        }
        else
        {
             echo '<script> alert("'.$this->conn->error.'");</script>' ;
        }
    }

    function update($query,$msg)
    { 
        if($this->conn->query($query)===TRUE)
        {
            echo '<script> Swal.fire({ title: "Success", text: "'.$msg.'", icon: "success", confirmButtonText: "OK" }); </script>' ; 
        }
        else
        {
            echo '<script> Swal.fire({ title: "Error", text: "'.$this->conn->error.'", icon: "error", confirmButtonText: "OK" }); </script>' ;
        }
    }
}   
?>
