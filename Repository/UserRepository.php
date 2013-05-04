<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Uzair
 * Date: 2/17/13
 * Time: 8:33 PM
 * To change this template use File | Settings | File Templates.
 */



class UserRepository
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

    public function Delete($ID)
    {
        if($ID==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        $sqlStatement =" Update tbl_users set Active=0"
            ." where User_ID=".$ID;
        echo $sqlStatement;
        $this->sqlConnection->CustomQuery($sqlStatement);
    }

    public function Add($userModel= null)
    {
        if($userModel==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        //$userModel=new UserModel();

        $sqlStatement = "insert into tbl_users
            (First_Name, Last_Name, User_Name,
            Password,
            Role_ID,
            Active,
            Created_On,
            Created_By,
            Locked
            )
            values
            ("
            ."'".$userModel->FirstName."',"
            ."'".$userModel->LastName."',"
            ."'".$userModel->UserName."',"
            ."'".$userModel->Password."',"

            ."'".$userModel->RoleID."',"
            ."'".$userModel->Active."',"
            ."'".$userModel->CreatedOn."',"

            ."'".$userModel->CreatedBy."',"
            ."'".$userModel->Locked."'"
            ."); ";



        echo  mysql_insert_id();

        $this->sqlConnection->Insert($sqlStatement);
        $newIDs = $this->sqlConnection->CustomQuery("select LAST_INSERT_ID() as ID;");
        foreach($newIDs as $newID)
        {
            return $newID["ID"];
        }
        //return
        //return  mysql_insert_id();

    }

    public function GetUserByID($id)
    {
        $user["ID"]="1";
        $user["UserID"]="userID";
        $user["Password"]="password";
        $user["Email"]="email@email.com";
        $user["FirstName"]="First Name";
        $user["LastName"]="Last Name";
        $user["RegistrationDate"]="1/12/2012";

        if($id== $user["ID"])
            return $user;
        else
            return null;
    }


    public function GetByUserName($userName)
    {
        if($userName==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        $sqlStatement =" select * from tbl_users "
            ."where User_Name='".$userName."'";
//echo $sqlStatement;
        $result = $this->sqlConnection->CustomQuery($sqlStatement);
        return $result;
    }

    public  function  GetAll()
    {
        $results = $this->sqlConnection->CustomQuery("SELECT * FROM tbl_users WHERE Active =1");

        /* foreach($results as $result)
        {
        echo $result["Crime_ID"];
        }

        print_r($results);*/
        return $results;
    }

}



?>
