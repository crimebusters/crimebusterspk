<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Uzair
 * Date: 2/17/13
 * Time: 10:58 PM
 * To change this template use File | Settings | File Templates.
 */
class SQL_Connection
{
    public $userName="";
    public $password="";
    public $connectionHost="";
    public $dbName="crimereports";
    private  $connection=null;

    function __construct()
    {
    }

    public function Connect()
    {
        if($this->connectionHost==null || $this->connectionHost==""||$this->dbName ==null || $this->dbName=="")
        {
            throw new Exception("Host and DBName must provided");
        }
        $this->connection=  mysqli_connect($this->connectionHost,$this->userName,$this->password,$this->dbName);
        if(!$this->connection || $this->connection==null)
        {
            throw new Exception('Could not connect: ' . mysql_error());
        }
        else
        {
            return true;
        }
    }

    public  function  Disconnect()
    {
        if($this->connection!=null && $this->connection)
        {
          //  mysql_close($this->connection);
        }
    }

    public function Insert($sql)
    {
        //$this->Connect();
        //echo "The Query is ".$sql;
        if($sql==""||$sql==null)
        {
            echo "Not Connected";
            throw new Exception("Error: SQL query must be provided");
        }
        // echo $sql;

        $succeed = mysqli_query($this->connection,$sql);

        //   echo $succeed;
    }

    public function Update($sql)
    {
        if($sql==""||$sql==null)
        {
            throw new Exception("Error: SQL query must be provided");
        }

        mysqli_query($this->connection,$sql);
    }

    public function Delete($sql)
    {
        if($sql==""||$sql==null)
        {
            throw new Exception("Error: SQL query must be provided");
        }
        mysqli_query($this->connection,$sql);
    }

    public function CustomQuery($sql)
    {
        if($sql==""||$sql==null)
        {
            throw new Exception("Error: SQL query must be provided");
        }
        $result = mysqli_query($this->connection,$sql);
        return $result;
    }

    public function GetAll($tableName)
    {
        if($tableName==""||$tableName==null)
        {
            throw new Exception("Error: Table name  must be provided");
        }
        $query ="SELECT * FROM ".$tableName;
        //echo "Query:- ".$query;

        $result = mysqli_query($this->connection,$query);
        return $result;
    }




}
