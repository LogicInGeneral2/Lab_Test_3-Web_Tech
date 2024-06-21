<?php
class db
{
    // Properties
    private $host = 'localhost';
    private $user = 'admin';
    private $password = 'admin';
    private $dbname = 'web_tech';

    // Connect
    function connect()
    {
        $mysql_connect_str = "mysql:host=$this->host;dbname=$this->dbname";
        $dbConnection = new PDO($mysql_connect_str, $this->user, $this->password);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}
