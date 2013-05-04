<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Uzair
 * Date: 2/17/13
 * Time: 8:47 PM
 * To change this template use File | Settings | File Templates.
 */
include_once("../Model/UserModel.php");

include_once("../Repository/SQL_Connection.php");//Place it before all the repositories
include_once("../Repository/UserRepository.php");



$userModel = new UserModel();

$userModel->FirstName=$_POST["firstName"];
$userModel->LastName=$_POST["lastName"];
$userModel->UserName=$_POST["email"];

$userModel->Password=$_POST["password"];
$userModel->RoleID =$_POST["userRole"];
$userModel->CreatedOn = getdate();

$userModel->CreatedBy = "sysadmin";
$userModel->Active = "1";
$userModel->Locked = "0";

$userRepository = new UserRepository();
$userRepository->Add($userModel);

header("location:../Users.php");

?>
