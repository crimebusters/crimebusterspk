<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 3/8/13
 * Time: 12:16 PM
 * To change this template use File | Settings | File Templates.
 */

//include ("SQL_Connection.php");


class CrimeRepository
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


    public function Add($crimeModel= null)
    {
        if($crimeModel==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

//Temporary only for development
//$crimeModel = new CrimeModel();
//$this->sqlConnection=new SQL_Connection();
        /*
        echo $crimeModel->CrimeCategoryID;
        echo $categoryID;
        */

        $categoryID=$crimeModel->CrimeCategoryID;

        $sqlStatement = "insert into tbl_crimes "
            ."( Crime_Category_ID, Crime_Description, Location_Name,
Location_Latitude,
Location_Longitude,
Votes_For,
Votes_Against,
Reported_By_User_IP,
Reported_By_User_Mac,
Crime_Date,
Crime_Time,
Other_Detail,
Severity_ID,
Created_On,
Created_By
)
values
("
            ."'".$categoryID."',"
            ."'".$crimeModel->CrimeDescription."',"
            ."'".$crimeModel->LocationName."',"
            ."'".$crimeModel->LocationLatitude."',"
            ."'".$crimeModel->LocationLongitude."',"

            ."'".$crimeModel->VotesFor."',"
            ."'".$crimeModel->VotesAgainst."',"
            ."'".$crimeModel->ReportedByUserIP."',"

            ."'".$crimeModel->ReportedByUserMac."',"
            ."'".$crimeModel->CrimeDate."',"
            ."'".$crimeModel->CrimeTime."',"

            ."'".$crimeModel->OtherDetail."',"
            ."'".$crimeModel->SeverityID."',"
            ."'".$crimeModel->CreatedOn."',"

            ."'".$crimeModel->CreatedBy."'"
            .")";

        echo  mysql_insert_id();

        $this->sqlConnection->Insert($sqlStatement);
        $newIDs = $this->sqlConnection->CustomQuery("select LAST_INSERT_ID() as ID;");
        foreach($newIDs as $newID)
        {
            return $newID["ID"];
        }
    }

    public function Update($crimeModel= null)
    {
        if($crimeModel==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

//Temporary only for development
//$crimeModel = new CrimeModel();
//$this->sqlConnection=new SQL_Connection();

        $sqlStatement =" update crime "
            ."set "
            ."CategoryID='".$crimeModel->CategoryID."',"
            ."LocationID='".$crimeModel->LocationID."',"
            ."Description='".$crimeModel->Description."',"
            ."DateTime='".$crimeModel->DateTime."',"
            ."Votes='".$crimeModel->Votes."',"
            ."CrimeDateTime='".$crimeModel->CrimeDateTime."'"
            ."where ID=".$crimeModel->ID;

//echo $sqlStatement;
// $sqlStatement="SELECT * FROM Category";
        $this->sqlConnection->Update($sqlStatement);
    }


    public function ApproveCrime($flag,$ID,$userID)
    {
        if($flag==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

//Temporary only for development
//$crimeModel = new CrimeModel();
//$this->sqlConnection=new SQL_Connection();
//echo "done";
        $sqlStatement =" update tbl_crimes "
            ."set "
            ."Approved=".$flag.", "
            ."Approved_By=".$userID.", "
            ."Approval_DateTime='".date("d/m/Y h:i:s a", time())."' "
            ."where Crime_ID=".$ID;

        echo $sqlStatement;
// $sqlStatement="SELECT * FROM Category";
        $this->sqlConnection->Update($sqlStatement);
    }

    public function Delete($ID)
    {
        if($ID==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        $sqlStatement =" delete crime "
            ."where ID=".$ID;

        $this->sqlConnection->Delete($sqlStatement);
    }

    public function Get($ID)
    {
        if($ID==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        $sqlStatement =" select * from tbl_crimes "
            ."where Crime_ID=".$ID;

        $result = $this->sqlConnection->CustomQuery($sqlStatement);

        return $result;
    }

    public function GetAll()
    {
        $results = $this->sqlConnection->GetAll("tbl_crimes");

        /* foreach($results as $result)
        {
        echo $result["Crime_ID"];
        }

        print_r($results);*/
        return $results;
    }

    public function GetUnApproved()
    {
        $sqlStatement =" select * from tbl_crimes "
            ."where Approved is null ";
//echo $sqlStatement;
        $results = $this->sqlConnection->CustomQuery($sqlStatement);
        /*
        foreach($results as $result)
        {echo "inside the loop";
        echo $result["Crime_ID"];
        }
        */
//print_r($results);
        return $results;
    }
}