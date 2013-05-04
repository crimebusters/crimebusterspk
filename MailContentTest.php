<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 4/26/13
 * Time: 2:51 PM
 * To change this template use File | Settings | File Templates.
 */



$userIP="192.168.80.199";
$crimeDescription="The Crime Description";



$message = "A new crime is report is waiting for your approval.";

$message .= "<br/><br/>";

$message .= "<br/>Date:- ".date("m/d/Y");
$message .= "<br/>Time:- ".date("h:i:s a", time());

$message .= "<br/>User IP:- ".$userIP;
$message .= "<br/>Description:- ".$crimeDescription;

$message .= "<br/><br/><br/><br/><br/><a href='http://localhost:8085/ReportCrime/ApproveDecline.php?flag=1&CrimeID=53&userID=18'".">Approve</a>";

$message .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

$message .= "<a href='http://localhost:8085/ReportCrime/ApproveDecline.php?flag=0&CrimeID=53&userID=18'".">Decline</a>";



echo $message;