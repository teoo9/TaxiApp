<?php
class Database{
    private $host="localhost";
    private $username="root";
    private $password="";
    private $name="taksi";

    function connect(){
        return mysqli_connect($this->host,$this->username,$this->password,$this->name);
    }

}
?>