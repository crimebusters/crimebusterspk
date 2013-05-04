<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Uzair
 * Date: 2/17/13
 * Time: 8:21 PM
 * To change this template use File | Settings | File Templates.
 */
class UserModel
{
    function __construct()
    {
    }
    public   $UserID= 0;
    public  $FirstName = "";
    public  $LastName = "";
    public  $UserName = "";
    public  $RoleID = "";
    public  $Password = "";
    public  $Active = "";
    public  $CreatedOn="";
    public  $CreatedBy="";
    public  $LastModifiedOn="";
    public  $LastModifiedBy="";
    public  $Locked="";




    /*
    public function GetFirstName()
    {
        return $this->firstrName;
    }
    public function GetLastName()
    {
        return $this->lastName;
    }
    public function GetFullName()
    {
        return $this->firstrName." ".$this->lastName;
    }
    */

}

?>
