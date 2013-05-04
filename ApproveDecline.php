<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 4/26/13
 * Time: 2:39 PM
 * To change this template use File | Settings | File Templates.
 */

include ("Repository/SQL_Connection.php");
include_once("Repository/CrimeRepository.php");
include_once("Model/CrimeModel.php");

echo $_GET["CrimeID"];
echo "<br/>";
echo $_GET["flag"];

$crimeRepository = new CrimeRepository();
echo "done";
$crimeRepository->ApproveCrime($_GET["flag"],$_GET["CrimeID"], $_GET["userID"])

//$crimeRepository->Update()


?>