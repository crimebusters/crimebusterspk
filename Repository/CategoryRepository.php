<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 3/1/13
 * Time: 11:58 AM
 * To change this template use File | Settings | File Templates.
 */

//include "SQL_Connection.php";
class CategoryRepository
{
    private  $sqlConnection=null;
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

    public  function Add($categoryModel= null)
    {
        if($categoryModel==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        //Temporary only for development
        //$categoryModel = new CategoryModel();
        //$this->sqlConnection=new SQL_Connection();

        $sqlStatement =" insert into category"
            ." ( Name, Description, DefinedBy, CreatedDate)"
            ." values "
            ." ('".$categoryModel->Name."', '".$categoryModel->Description."'"
            .", '".$categoryModel->DefinedBy."', '".$categoryModel->CreatedDate."')";

        //echo $sqlStatement;
        // $sqlStatement="SELECT * FROM Category";
        $this->sqlConnection->Insert($sqlStatement);
        $this->CloseConnection();


    }


    public  function Update(CategoryModel $categoryModel= null)
    {
        if($categoryModel==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }


        $sqlStatement =" update category "
            ."set "
            ."Name='".$categoryModel->Name."',"
            ."DefinedBy='".$categoryModel->DefinedBy."',"
            ."Description='".$categoryModel->Description."',"
            ."CreatedDate='".$categoryModel->CreatedDate."'"
            ."where ID=".$categoryModel->ID;

        //echo $sqlStatement;
        // $sqlStatement="SELECT * FROM Category";
        $this->sqlConnection->Update($sqlStatement);
        $this->CloseConnection();
    }

    public  function Delete($ID)
    {
        if($ID==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        $sqlStatement =" delete category "
            ."where ID=".$ID;

        $this->sqlConnection->Delete($sqlStatement);
        $this->CloseConnection();
    }

    public  function Get($ID)
    {
        if($ID==null)
        {
            throw new InvalidArgumentException("Null argument provided");
        }

        $sqlStatement =" select * from category "
            ."where ID=".$ID;

        $result =  $this->sqlConnection->CustomQuery($sqlStatement);
        $this->CloseConnection();
        return $result;
    }

    public  function GetAll()
    {
        $result =  $this->sqlConnection->GetAll("tbl_categories");
        $this->CloseConnection();
        return $result;
    }

}
