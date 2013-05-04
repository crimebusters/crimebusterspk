<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 5/3/13
 * Time: 6:47 PM
 * To change this template use File | Settings | File Templates.
 */
include ("../Repository/SQL_Connection.php");
require_once ("../Repository/UserRepository.php");

session_start();

$userEmail = $_POST["email"];
$userPassword = $_POST["password"];

if($userEmail=="" ||$userEmail==null )
{
    header("location:../login.php");
    //return;
}

$logOutFlag = $_GET["logout"];

if($logOutFlag == true)
{
    session_destroy();
    header("location:../login.php");
}

$userRepository = new UserRepository();
$userRow = $userRepository->GetByUserName($userEmail);

$userName="";
$password="";
$userID="";
$firstName="";
$lastName="";
$roleID="";

//print_r($userRow);

foreach($userRow as $user)
{
    $userName=$user["User_Name"];
    $password = $user["Password"];
    $userID = $user["User_ID"];
    $firstName = $user["First_Name"];
    $lastName = $user["Last_Name"];
    $roleID = $user["Role_ID"];
    break;
}



if($password==$userPassword)
{
    $_SESSION['USER_EMAIL']=$userName;
    $_SESSION['USER_ID']=$userID;
    $_SESSION['USER_FIRST_NAME']=$firstName;
    $_SESSION['USER_LAST_NAME']=$lastName;
    $_SESSION['USER_PASSWORD']=$password;
    $_SESSION['USER_ROLE_ID']=$roleID;
    $_SESSION['IS_LOGGEDIN'] = true;

    header("location:../users.php");
}
else
{
    session_destroy();
    header("location:../login.php");
}









