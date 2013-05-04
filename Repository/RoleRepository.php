<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Uzair
 * Date: 2/17/13
 * Time: 8:33 PM
 * To change this template use File | Settings | File Templates.
 */



class RoleRepository
{
    private $sqlConnection=null;
    function __construct()
    {
        $this->sqlConnection=new SQL_Connection();
        $this->sqlConnection->connectionHost="localhost";
        $this->sqlConnection->userName="root";
        $this->sqlConnection->Connect();
    }

    public function CloseConnection()
    {
        $this->sqlConnection->Disconnect();
    }




    public  function  GetAll()
    {
        $results = $this->sqlConnection->GetAll("tbl_roles");

        /* foreach($results as $result)
        {
        echo $result["Crime_ID"];
        }

        print_r($results);*/
        return $results;
    }

}



?>
