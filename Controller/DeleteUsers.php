<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 5/3/13
 * Time: 7:49 PM
 * To change this template use File | Settings | File Templates.
 */
include ("../Repository/SQL_Connection.php");
require_once ("../Repository/UserRepository.php");

session_start();

$flag = $_SESSION["IS_LOGGEDIN"];
//echo  $flag;
//echo $_SESSION["USER_EMAIL"];

if($flag != "true")
{
    header("location:../Login.php");
    return;
}

$userID = $_GET["userID"];

if($userID==null|| $userID=="")
{
    header("location:../users.php");
}

$repository = new UserRepository();
$repository->Delete($userID);

header("location:../users.php");



