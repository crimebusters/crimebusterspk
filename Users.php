<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 5/3/13
 * Time: 5:46 PM
 * To change this template use File | Settings | File Templates.
 */

include ("Repository/SQL_Connection.php");
require_once ("Repository/UserRepository.php");

session_start();

$flag = $_SESSION["IS_LOGGEDIN"];
//echo  $flag;
//echo $_SESSION["USER_EMAIL"];

if($flag != "true")
{
    header("location:Login.php");
    return;
}

$userRepository = new UserRepository();
$users = $userRepository->GetAll();

$pageName = "window.location=\"AddUsers.php\"";
echo "<input type='button' value='Add New User' onclick='".$pageName. "'>";

//echo "<br/>";

$pageName = "Controller/LoginController.php?logout=true";
echo "<a href='".$pageName."'> Logout </a>";

echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<table>";

echo "<tr>";

echo "<td> ";
echo "User Name";
echo "</td>";


echo "<td colsapn=2> ";
echo "Action";
echo "</td>";

echo "</tr>";

foreach($users as $user)
{
    echo "<tr>";

    echo "<td style='width:200px;'> ";
    echo $user["First_Name"]." ".$user["Last_Name"];
    echo "</td>";


    echo "<td> ";
    echo "Edit";
    echo "</td>";

    echo "<td> ";
    echo "<a href=Controller/DeleteUsers.php?userID=". $user["User_ID"] .">Delete</a>";
    echo "</td>";

    echo "</tr>";
}
echo "</table>";

